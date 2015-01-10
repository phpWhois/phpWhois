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

namespace phpWhois;

/**
 * phpWhois main class
 *
 * This class supposed to be instantiated for using the phpWhois library
 */
class Whois extends WhoisClient
{

    /** @var boolean Deep whois? */
    public $deepWhois = true;

    /** @inheritdoc */
    public $gtldRecurse = true;

    /** @var array Query array */
    public $query = array();

    /** @var string Network Solutions registry server */
    public $nsiRegistry = 'whois.nsiregistry.net';

    const QTYPE_UNKNOWN = 0;
    const QTYPE_DOMAIN  = 1;
    const QTYPE_IPV4    = 2;
    const QTYPE_IPV6    = 3;
    const QTYPE_AS      = 4;

    /**
     * Use special whois server (Populate WHOIS_SPECIAL array)
     *
     * @param string $tld Top-level domain
     * @param string $server Server address
     */
    public function useServer($tld, $server)
    {
        $this->WHOIS_SPECIAL[$tld] = $server;
    }

    /**
     *  Lookup query
     *
     * @param string $query Domain name or other entity
     * @param boolean $is_utf True if domain name encoding is utf-8 already, otherwise convert it with utf8_encode() first
     *
     */
    public function lookup($query = '', $is_utf = true)
    {
        // start clean
        $this->query = array('status' => '');

        $query = trim($query);

        $idn = new \idna_convert();

        if ($is_utf) {
            $query = $idn->encode($query);
        } else {
            $query = $idn->encode(utf8_encode($query));
        }

        // If domain to query was not set
        if (!isset($query) || $query == '') {
            // Configure to use default whois server
            $this->query['server'] = $this->nsiRegistry;
            return;
        }

        // Set domain to query in query array
        $this->query['query'] = $domain = $query = strtolower($query);

        // Find a query type
        $qType = $this->getQueryType($query);

        switch ($qType) {
            case self::QTYPE_IPV4:
                // IPv4 Prepare to do lookup via the 'ip' handler
                $ip = @gethostbyname($query);

                if (isset($this->WHOIS_SPECIAL['ip'])) {
                    $this->query['server'] = $this->WHOIS_SPECIAL['ip'];
                    $this->query['args'] = $ip;
                } else {
                    $this->query['server'] = 'whois.arin.net';
                    $this->query['args'] = "n $ip";
                    $this->query['file'] = 'whois.ip.php';
                    $this->query['handler'] = 'ip';
                }
                $this->query['host_ip'] = $ip;
                $this->query['query'] = $ip;
                $this->query['tld'] = 'ip';
                $this->query['host_name'] = @gethostbyaddr($ip);

                return $this->getData('', $this->deepWhois);
                break;

            case self::QTYPE_IPV6:
                // IPv6 AS Prepare to do lookup via the 'ip' handler
                $ip = @gethostbyname($query);

                if (isset($this->WHOIS_SPECIAL['ip'])) {
                    $this->query['server'] = $this->WHOIS_SPECIAL['ip'];
                } else {
                    $this->query['server'] = 'whois.ripe.net';
                    $this->query['file'] = 'whois.ip.ripe.php';
                    $this->query['handler'] = 'ripe';
                }
                $this->query['query'] = $ip;
                $this->query['tld'] = 'ip';
                return $this->getData('', $this->deepWhois);
                break;

            case self::QTYPE_AS:
                // AS Prepare to do lookup via the 'ip' handler
                $ip = @gethostbyname($query);
                $this->query['server'] = 'whois.arin.net';
                if (strtolower(substr($ip, 0, 2)) == 'as') {
                    $as = substr($ip, 2);
                } else {
                    $as = $ip;
                }
                $this->query['args'] = "a $as";
                $this->query['file'] = 'whois.ip.php';
                $this->query['handler'] = 'ip';
                $this->query['query'] = $ip;
                $this->query['tld'] = 'as';
                return $this->getData('', $this->deepWhois);
                break;
        }

        // Build array of all possible tld's for that domain
        $tld = '';
        $server = '';
        $dp = explode('.', $domain);
        $np = count($dp) - 1;
        $tldtests = array();

        for ($i = 0; $i < $np; $i++) {
            array_shift($dp);
            $tldtests[] = implode('.', $dp);
        }

        // Search the correct whois server
        $special_tlds = $this->WHOIS_SPECIAL;

        foreach ($tldtests as $tld) {
            // Test if we know in advance that no whois server is
            // available for this domain and that we can get the
            // data via http or whois request
            if (isset($special_tlds[$tld])) {
                $val = $special_tlds[$tld];

                if ($val == '') {
                    return $this->unknown();
                }

                $domain = substr($query, 0, -strlen($tld) - 1);
                $val = str_replace('{domain}', $domain, $val);
                $server = str_replace('{tld}', $tld, $val);
                break;
            }
        }

        if ($server == '') {
            foreach ($tldtests as $tld) {
                // Determine the top level domain, and it's whois server using
                // DNS lookups on 'whois-servers.net'.
                // Assumes a valid DNS response indicates a recognised tld (!?)
                $cname = $tld . '.whois-servers.net';

                if (gethostbyname($cname) == $cname) {
                    continue;
                }
                $server = $tld . '.whois-servers.net';
                break;
            }
        }

        if ($tld && $server) {
            // If found, set tld and whois server in query array
            $this->query['server'] = $server;
            $this->query['tld'] = $tld;
            $handler = '';

            foreach ($tldtests as $htld) {
                // special handler exists for the tld ?
                if (isset($this->DATA[$htld])) {
                    $handler = $this->DATA[$htld];
                    break;
                }

                // Regular handler exists for the tld ?
                if (file_exists('whois.' . $htld . '.php')) {
                    $handler = $htld;
                    break;
                }
            }

            // If there is a handler set it
            if ($handler != '') {
                $this->query['file'] = "whois.$handler.php";
                $this->query['handler'] = $handler;
            }

            // Special parameters ?
            if (isset($this->WHOIS_PARAM[$server])) {
                $this->query['server'] = $this->query['server'] . '?' . str_replace('$', $domain,
                        $this->WHOIS_PARAM[$server]);
            }

            $result = $this->getData('', $this->deepWhois);
            $this->checkDns($result);
            return $result;
        }

        // If tld not known, and domain not in DNS, return error
        return $this->unknown();
    }

    /**
     * Unsupported domains
     */
    public function unknown()
    {
        unset($this->query['server']);
        $this->query['status'] = 'error';
        $result = array('rawdata' => array());
        $result['rawdata'][] = $this->query['errstr'][] = $this->query['query'] . ' domain is not supported';
        $this->checkDns($result);
        $this->fixResult($result, $this->query['query']);
        return $result;
    }

    /**
     * Get nameservers if missing
     */
    public function checkDns(&$result)
    {
        if ($this->deepWhois && empty($result['regrinfo']['domain']['nserver'])) {
            $ns = @dns_get_record($this->query['query'], DNS_NS);
            if (!is_array($ns)) {
                return;
            }
            $nserver = array();
            foreach ($ns as $row) {
                $nserver[] = $row['target'];
            }
            if (count($nserver) > 0) {
                $result['regrinfo']['domain']['nserver'] = $this->fixNameServer($nserver);
            }
        }
    }

    /**
     *  Fix and/or add name server information
     */
    public function fixResult(&$result, $domain)
    {
        // Add usual fields
        $result['regrinfo']['domain']['name'] = $domain;

        // Check if nameservers exist
        if (!isset($result['regrinfo']['registered'])) {
            if (function_exists('checkdnsrr') && checkdnsrr($domain, 'NS')) {
                $result['regrinfo']['registered'] = 'yes';
            } else {
                $result['regrinfo']['registered'] = 'unknown';
            }
        }

        // Normalize nameserver fields
        if (isset($result['regrinfo']['domain']['nserver'])) {
            if (!is_array($result['regrinfo']['domain']['nserver'])) {
                unset($result['regrinfo']['domain']['nserver']);
            } else {
                $result['regrinfo']['domain']['nserver'] = $this->fixNameServer($result['regrinfo']['domain']['nserver']);
            }
        }
    }

    /**
     * Guess query type
     *
     * @param string $query
     *
     * @return int Query type
     */
    public function getQueryType($query)
    {
        $ipTools = new IpTools;

        if ($ipTools->validIp($query, 'ipv4', false)) {
            if ($ipTools->validIp($query, 'ipv4')) {
                return self::QTYPE_IPV4;
            } else {
                return self::QTYPE_UNKNOWN;
            }
        } elseif ($ipTools->validIp($query, 'ipv6', false)) {
            if ($ipTools->validIp($query, 'ipv6')) {
                return self::QTYPE_IPV6;
            } else {
                return self::QTYPE_UNKNOWN;
            }
        } elseif (!empty($query) && strpos($query, '.') !== false) {
            return self::QTYPE_DOMAIN;
        } elseif (!empty($query) && strpos($query, '.') === false) {
            return self::QTYPE_AS;
        } else {
            return self::QTYPE_UNKNOWN;
        }
    }
}
