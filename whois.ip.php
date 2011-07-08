<?php
/*
Whois.php        PHP classes to conduct whois queries

Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic

Maintained by David Saez

For the most recent version of this package visit:

http://www.phpwhois.org

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

if (!defined('__IP_HANDLER__'))
	define('__IP_HANDLER__', 1);

require_once('whois.ip.lib.php');

class ip_handler extends WhoisClient
	{
	// Deep whois ?
	var $deep_whois = true;

	var $HANDLER_VERSION = '1.0';

	var $REGISTRARS = array(
                        'European Regional Internet Registry/RIPE NCC' =>	'whois.ripe.net',
                        'RIPE Network Coordination Centre' => 'whois.ripe.net',
                        'Asia Pacific Network Information	Center' => 'whois.apnic.net',
                        'Asia Pacific Network Information Centre' => 'whois.apnic.net',
                        'Latin American and Caribbean IP address Regional Registry'	=> 'whois.lacnic.net',
                        'African Network Information Center' => 'whois.afrinic.net'
	                     );

	var $HANDLERS = array(
						'whois.krnic.net' => 'krnic',
						'whois.apnic.net' => 'apnic',
						'whois.ripe.net' => 'ripe',
						'whois.arin.net' =>	'arin',
						'whois.lacnic.net' => 'lacnic',
						'whois.afrinic.net' => 'afrinic'
	                     );

	var $more_data = array();	// More queries to get more accurated data
	var $done = array();

	function parse($data, $query)
		{
		$result['regrinfo'] = array();
		$result['regyinfo'] = array();
		$result['regyinfo']['registrar'] = 'American Registry for Internet Numbers (ARIN)';
		$result['rawdata'] = array();

		if (strpos($query,'.') === false)
			$result['regyinfo']['type'] = 'AS';
		else
			$result['regyinfo']['type'] = 'ip';

		if (!$this->deep_whois) return null;

		$this->Query = array();
		$this->Query['server'] = 'whois.arin.net';
		$this->Query['query'] = $query;

		$rawdata = $data['rawdata'];

		if (empty($rawdata)) return $result;

		$presults[] = $rawdata;
		$ip = ip2long($query);
		$done = array();

		while (count($presults) > 0)
			{
			$rwdata = array_shift($presults);
			$found = false;

			foreach($rwdata as $line)
				{
				if (!strncmp($line,'American Registry for Internet Numbers',38)) continue;

				$p = strpos($line, '(NETBLK-');

				if ($p === false) $p = strpos($line, '(NET-');

				if ($p !== false)
					{
					$net = strtok(substr($line,$p+1),') ');
					list($low,$high) = explode('-',str_replace(' ','',substr($line,$p+strlen($net)+3)));

					if (!isset($done[$net]) && $ip >= ip2long($low) && $ip <= ip2long($high))
						{
						$owner = substr($line,0,$p-1);

						if (!empty($this->REGISTRARS['owner']))
							{
							$this->handle_rwhois($this->REGISTRARS['owner'],$query);
							break 2;
							}
						else
							{
							$this->Query['args'] = 'n '.$net;
							$presults[] = $this->GetRawData($net);
							$done[$net] = 1;
							}
						}
					$found = true;
					}
				}

			if (!$found)
				{
				$this->Query['file'] = 'whois.ip.arin.php';
				$this->Query['handler'] = 'arin';
				$result = $this->parse_results($result,$rwdata,$query,true);
				}
			}

		unset($this->Query['args']);

		while (count($this->more_data) > 0)
			{
			$srv_data = array_shift($this->more_data);
			$this->Query['server'] = $srv_data['server'];
			unset($this->Query['handler']);
			// Use original query
			$rwdata = $this->GetRawData($srv_data['query']);

			if (!empty($rwdata))
				{
				if (!empty($srv_data['handler']))
					{
					$this->Query['handler'] = $srv_data['handler'];

					if (!empty($srv_data['file']))
						$this->Query['file'] = $srv_data['file'];
					else
						$this->Query['file'] = 'whois.'.$this->Query['handler'].'.php';
					}

				$result = $this->parse_results($result,$rwdata,$query,$srv_data['reset']);
				$result = $this->set_whois_info($result);
				$reset = false;
				}
			}


		// Normalize nameserver fields

		if (isset($result['regrinfo']['network']['nserver']))
			{
			if (!is_array($result['regrinfo']['network']['nserver']))
				{
				unset($result['regrinfo']['network']['nserver']);
				}
			else
				$result['regrinfo']['network']['nserver'] = $this->FixNameServer($result['regrinfo']['network']['nserver']);
			}

		return $result;
		}

	//-----------------------------------------------------------------

	function parse_results($result,$rwdata,$query,$reset)
		{
		$rwres = $this->Process($rwdata);

		if ($result['regyinfo']['type'] == 'AS' && !empty($rwres['regrinfo']['network']))
			{
			$rwres['regrinfo']['AS'] = $rwres['regrinfo']['network'];
			unset($rwres['regrinfo']['network']);
			}

		if ($reset)
			{
			$result['regrinfo'] = $rwres['regrinfo'];
			$result['rawdata'] = $rwdata;
			}
		else
			{
			$result['rawdata'][] = '';

			foreach ($rwdata as $line)
				$result['rawdata'][] = $line;

			foreach($rwres['regrinfo'] as $key => $data)
				{
				$result = $this->join_result($result,$key,$rwres);
				}
			}

		if ($this->deep_whois)
			{
			if (isset($rwres['regrinfo']['rwhois']))
				{
				$this->handle_rwhois($rwres['regrinfo']['rwhois'],$query);
				unset($result['regrinfo']['rwhois']);
				}
			else
				if (!@empty($rwres['regrinfo']['owner']['organization']))
					switch ($rwres['regrinfo']['owner']['organization'])
						{
						case 'KRNIC':
							$this->handle_rwhois('whois.krnic.net',$query);
							break;

						case 'African Network Information Center':
							$this->handle_rwhois('whois.afrinic.net',$query);
							break;
						}
			}

		if (!empty($rwres['regyinfo']))
			$result['regyinfo'] = array_merge($result['regyinfo'],$rwres['regyinfo']);

		return $result;
		}

	//-----------------------------------------------------------------

	function handle_rwhois($server,$query)
		{
		// Avoid querying the same server twice

		$parts = parse_url($server);

		if (empty($parts['host']))
			$host = $parts['path'];
		else
			$host = $parts['host'];

		if (array_key_exists($host,$this->done)) return;

		$q = array (
					'query' => $query,
					'server' => $server
					);

		if (isset($this->HANDLERS[$host]))
			{
			$q['handler'] = $this->HANDLERS[$host];
			$q['file'] = sprintf('whois.ip.%s.php', $q['handler']);
			$q['reset'] = true;
			}
		else
			{
			$q['handler'] = 'rwhois';
			$q['reset'] = false;
			unset($q['file']);
			}

		$this->more_data[] = $q;
		$this->done[$host] = 1;
		}

	//-----------------------------------------------------------------

	function join_result($result, $key, $newres)
		{
		if (isset($result['regrinfo'][$key]) && !array_key_exists(0,$result['regrinfo'][$key]))
			{
			$r = $result['regrinfo'][$key];
			$result['regrinfo'][$key] = array($r);
			}

		$result['regrinfo'][$key][] = $newres['regrinfo'][$key];
		return $result;
		}
	}
?>