<?php
/**
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @license
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 * 
 * @link http://phpwhois.pw
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 */

if (!defined('__IP_HANDLER__'))
    define('__IP_HANDLER__', 1);

use phpWhois\WhoisClient;

class ip_handler extends WhoisClient {

    /** @var Deep whois? */
    public $deepWhois = true;
    public $REGISTRARS = array(
        'European Regional Internet Registry/RIPE NCC' => 'whois.ripe.net',
        'RIPE Network Coordination Centre' => 'whois.ripe.net',
        'Asia Pacific Network Information	Center' => 'whois.apnic.net',
        'Asia Pacific Network Information Centre' => 'whois.apnic.net',
        'Latin American and Caribbean IP address Regional Registry' => 'whois.lacnic.net',
        'African Network Information Center' => 'whois.afrinic.net'
    );
    public $HANDLERS = array(
        'whois.krnic.net' => 'krnic',
        'whois.apnic.net' => 'apnic',
        'whois.ripe.net' => 'ripe',
        'whois.arin.net' => 'arin',
        'whois.lacnic.net' => 'lacnic',
        'whois.afrinic.net' => 'afrinic'
    );
    public $more_data = array(); // More queries to get more accurated data
    public $done = array();

    public function parse($data, $query) {
        $result = array(
            'regrinfo' => array(),
            'regyinfo' => array(),
            'rawdata' => array(),
        );
        $result['regyinfo']['registrar'] = 'American Registry for Internet Numbers (ARIN)';

        if (strpos($query, '.') === false)
            $result['regyinfo']['type'] = 'AS';
        else
            $result['regyinfo']['type'] = 'ip';

        if (!$this->deepWhois)
            return null;

        $this->query = array();
        $this->query['server'] = 'whois.arin.net';
        $this->query['query'] = $query;

        $rawdata = $data['rawdata'];

        if (empty($rawdata))
            return $result;

        $presults = array();
        $presults[] = $rawdata;
        $ip = ip2long($query);
        $done = array();

        while (count($presults) > 0) {
            $rwdata = array_shift($presults);
            $found = false;

            foreach ($rwdata as $line) {
                if (!strncmp($line, 'American Registry for Internet Numbers', 38))
                    continue;

                $p = strpos($line, '(NETBLK-');

                if ($p === false)
                    $p = strpos($line, '(NET-');

                if ($p !== false) {
                    $net = strtok(substr($line, $p + 1), ') ');
                    list($low, $high) = explode('-', str_replace(' ', '', substr($line, $p + strlen($net) + 3)));

                    if (!isset($done[$net]) && $ip >= ip2long($low) && $ip <= ip2long($high)) {
                        $owner = substr($line, 0, $p - 1);

                        if (!empty($this->REGISTRARS['owner'])) {
                            $this->handle_rwhois($this->REGISTRARS['owner'], $query);
                            break 2;
                        } else {
                            $this->query['args'] = 'n ' . $net;
                            $presults[] = $this->getRawData($net);
                            $done[$net] = 1;
                        }
                    }
                    $found = true;
                }
            }

            if (!$found) {
                $this->query['file'] = 'whois.ip.arin.php';
                $this->query['handler'] = 'arin';
                $result = $this->parse_results($result, $rwdata, $query, true);
            }
        }

        unset($this->query['args']);

        while (count($this->more_data) > 0) {
            $srv_data = array_shift($this->more_data);
            $this->query['server'] = $srv_data['server'];
            unset($this->query['handler']);
            // Use original query
            $rwdata = $this->getRawData($srv_data['query']);

            if (!empty($rwdata)) {
                if (!empty($srv_data['handler'])) {
                    $this->query['handler'] = $srv_data['handler'];

                    if (!empty($srv_data['file']))
                        $this->query['file'] = $srv_data['file'];
                    else
                        $this->query['file'] = 'whois.' . $this->query['handler'] . '.php';
                }

                $result = $this->parse_results($result, $rwdata, $query, $srv_data['reset']);
                $result = $this->setWhoisInfo($result);
                $reset = false;
            }
        }


        // Normalize nameserver fields

        if (isset($result['regrinfo']['network']['nserver'])) {
            if (!is_array($result['regrinfo']['network']['nserver'])) {
                unset($result['regrinfo']['network']['nserver']);
            } else
                $result['regrinfo']['network']['nserver'] = $this->fixNameServer($result['regrinfo']['network']['nserver']);
        }

        return $result;
    }

    //-----------------------------------------------------------------

    public function parse_results($result, $rwdata, $query, $reset) {
        $rwres = $this->process($rwdata);

        if ($result['regyinfo']['type'] == 'AS' && !empty($rwres['regrinfo']['network'])) {
            $rwres['regrinfo']['AS'] = $rwres['regrinfo']['network'];
            unset($rwres['regrinfo']['network']);
        }

        if ($reset) {
            $result['regrinfo'] = $rwres['regrinfo'];
            $result['rawdata'] = $rwdata;
        } else {
            $result['rawdata'][] = '';

            foreach ($rwdata as $line)
                $result['rawdata'][] = $line;

            foreach ($rwres['regrinfo'] as $key => $data) {
                $result = $this->join_result($result, $key, $rwres);
            }
        }

        if ($this->deepWhois) {
            if (isset($rwres['regrinfo']['rwhois'])) {
                $this->handle_rwhois($rwres['regrinfo']['rwhois'], $query);
                unset($result['regrinfo']['rwhois']);
            } else
            if (!@empty($rwres['regrinfo']['owner']['organization']))
                switch ($rwres['regrinfo']['owner']['organization']) {
                    case 'KRNIC':
                        $this->handle_rwhois('whois.krnic.net', $query);
                        break;

                    case 'African Network Information Center':
                        $this->handle_rwhois('whois.afrinic.net', $query);
                        break;
                }
        }

        if (!empty($rwres['regyinfo']))
            $result['regyinfo'] = array_merge($result['regyinfo'], $rwres['regyinfo']);

        return $result;
    }

    //-----------------------------------------------------------------

    public function handle_rwhois($server, $query) {
        // Avoid querying the same server twice

        $parts = parse_url($server);

        if (empty($parts['host']))
            $host = $parts['path'];
        else
            $host = $parts['host'];

        if (array_key_exists($host, $this->done))
            return;

        $q = array(
            'query' => $query,
            'server' => $server
        );

        if (isset($this->HANDLERS[$host])) {
            $q['handler'] = $this->HANDLERS[$host];
            $q['file'] = sprintf('whois.ip.%s.php', $q['handler']);
            $q['reset'] = true;
        } else {
            $q['handler'] = 'rwhois';
            $q['reset'] = false;
            unset($q['file']);
        }

        $this->more_data[] = $q;
        $this->done[$host] = 1;
    }

    //-----------------------------------------------------------------

    public function join_result($result, $key, $newres) {
        if (isset($result['regrinfo'][$key]) && !array_key_exists(0, $result['regrinfo'][$key])) {
            $r = $result['regrinfo'][$key];
            $result['regrinfo'][$key] = array($r);
        }

        $result['regrinfo'][$key][] = $newres['regrinfo'][$key];
        return $result;
    }

}
