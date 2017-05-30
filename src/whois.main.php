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

require_once('whois.client.php');
require_once('whois.idna.php');

class Whois extends WhoisClient
	{
	// Deep whois ?
	var $deep_whois = true;

	// Windows based ?
	var $windows = false;

	// Recursion allowed ?
	var $gtld_recurse = true;

	// Support for non-ICANN tld's
	var $non_icann = false;

	// Network Solutions registry server
	var $NSI_REGISTRY = 'whois.nsiregistry.net';

	/*
	 * Constructor function
	 */
	function __construct()
		{
		// Load DATA array
		@require('whois.servers.php');

		if ( ( substr( php_uname(), 0, 7 ) == 'Windows' ) )
			$this->windows = true;
		else
			$this->windows = false;

		// Set version
		$this->VERSION = sprintf("phpWhois v%s-%s", $this->CODE_VERSION, $this->DATA_VERSION);
		}

	/*
	 *  Use special whois server
	 */

	function UseServer ($tld, $server)
		{
		$this->WHOIS_SPECIAL[$tld] = $server;
		}

	/*
	 *  Lookup query
	 */

	function Lookup($query = '', $is_utf = true)
		{
		// start clean
		$this->Query = array( 'status' => '' );

		$query = trim($query);

		$IDN = new idna_convert();

		if ($is_utf)
			$query = $IDN->encode($query);
		else
			$query = $IDN->encode(utf8_encode($query));

		// If domain to query was not set
		if (!isSet($query) || $query == '')
			{
			// Configure to use default whois server
			$this->Query['server'] = $this->NSI_REGISTRY;
			return ;
			}

		// Set domain to query in query array

		$this->Query['query'] = $domain = strtolower($query);

		// If query is an ip address do ip lookup

		if ($query == long2ip(ip2long($query)))
			{
			// IPv4 Prepare to do lookup via the 'ip' handler
			$ip = @gethostbyname($query);

			if (isset($this->WHOIS_SPECIAL['ip']))
				{
				$this->Query['server'] = $this->WHOIS_SPECIAL['ip'];
				$this->Query['args'] = $ip;
				}
			else
				{
				$this->Query['server'] = 'whois.arin.net';
				$this->Query['args'] = "n $ip";
				$this->Query['file'] = 'whois.ip.php';
				$this->Query['handler'] = 'ip';
				}
			$this->Query['host_ip'] = $ip;
			$this->Query['query'] = $ip;
			$this->Query['tld'] = 'ip';
			$this->Query['host_name'] = @gethostbyaddr($ip);
			return $this->GetData('',$this->deep_whois);
			}

		if (strpos($query, ':'))
			{
			// IPv6 AS Prepare to do lookup via the 'ip' handler
			$ip = @gethostbyname($query);

			if (isset($this->WHOIS_SPECIAL['ip']))
				{
				$this->Query['server'] = $this->WHOIS_SPECIAL['ip'];
				}
			else
				{
				$this->Query['server'] = 'whois.ripe.net';
				$this->Query['file'] = 'whois.ip.ripe.php';
				$this->Query['handler'] = 'ripe';
				}
			$this->Query['query'] = $ip;
			$this->Query['tld'] = 'ip';
			return $this->GetData('',$this->deep_whois);
			}

		if (!strpos($query, '.'))
			{
			// AS Prepare to do lookup via the 'ip' handler
			$ip = @gethostbyname($query);
			$this->Query['server'] = 'whois.arin.net';
			if (strtolower(substr($ip,0,2)) == 'as')
				$as = substr($ip,2);
			else
				$as = $ip;
			$this->Query['args'] = "a $as";
			$this->Query['file'] = 'whois.ip.php';
			$this->Query['handler'] = 'ip';
			$this->Query['query'] = $ip;
			$this->Query['tld'] = 'as';
			return $this->GetData('',$this->deep_whois);
			}

		// Build array of all possible tld's for that domain

		$tld = '';
		$server = '';
		$dp = explode('.', $domain);
		$np = count($dp)-1;
		$tldtests = array();

		for ($i = 0; $i < $np; $i++)
			{
			array_shift($dp);
			$tldtests[] = implode('.', $dp);
			}

		// Search the correct whois server

		if ($this->non_icann)
			$special_tlds = array_merge($this->WHOIS_SPECIAL,$this->WHOIS_NON_ICANN);
		else
			$special_tlds = $this->WHOIS_SPECIAL;

		foreach($tldtests as $tld)
			{
			// Test if we know in advance that no whois server is
			// available for this domain and that we can get the
			// data via http or whois request

			if (isset($special_tlds[$tld]))
				{
				$val = $special_tlds[$tld];

				if ($val == '') return $this->Unknown();

				$domain = substr($query, 0,  - strlen($tld) - 1);
				$val = str_replace('{domain}', $domain, $val);
				$server = str_replace('{tld}', $tld, $val);
				break;
				}
			}

		if ($server == '')
			foreach($tldtests as $tld)
				{
				// Determine the top level domain, and it's whois server using
				// DNS lookups on 'whois-servers.net'.
				// Assumes a valid DNS response indicates a recognised tld (!?)

				$cname = $tld.'.whois-servers.net';

				if (gethostbyname($cname) == $cname) continue;
				$server = $tld.'.whois-servers.net';
				break;
				}

		if ($tld && $server)
			{
			// If found, set tld and whois server in query array
			$this->Query['server'] = $server;
			$this->Query['tld'] = $tld;
			$handler = '';

			foreach($tldtests as $htld)
				{
				// special handler exists for the tld ?

				if (isSet($this->DATA[$htld]))
				    {
					$handler = $this->DATA[$htld];
					break;
					}

				// Regular handler exists for the tld ?
				if (($fp = @fopen('whois.'.$htld.'.php', 'r', 1)) and fclose($fp))
				    {
					$handler = $htld;
					break;
					}
				}

			// If there is a handler set it

			if ($handler != '')
				{
				$this->Query['file'] = "whois.$handler.php";
				$this->Query['handler'] = $handler;
				}

			// Special parameters ?

			if (isset($this->WHOIS_PARAM[$server]))
				$this->Query['server'] = $this->Query['server'].'?'.str_replace('$',$domain,$this->WHOIS_PARAM[$server]);

			$result = $this->GetData('',$this->deep_whois);
			$this->Checkdns($result);
			return $result;
			}

		// If tld not known, and domain not in DNS, return error
		return $this->Unknown();
		}

	/* Unsupported domains */

	function Unknown()
		{
		unset($this->Query['server']);
		$this->Query['status'] = 'error';
		$result['rawdata'][] = $this->Query['errstr'][] = $this->Query['query'].' domain is not supported';
		$this->Checkdns($result);
		$this->FixResult($result, $this->Query['query']);
		return $result;
		}

	/* Get nameservers if missing */

	function Checkdns(&$result)
		{
		if ($this->deep_whois && empty($result['regrinfo']['domain']['nserver']) && function_exists('dns_get_record'))
			{
			$ns = @dns_get_record($this->Query['query'],DNS_NS);
			if (!is_array($ns)) return;
			$nserver = array();
			foreach($ns as $row) $nserver[] = $row['target'];
			if (count($nserver) > 0)
				$result['regrinfo']['domain']['nserver'] = $this->FixNameServer($nserver);
			}
		}
	/*
	 *  Fix and/or add name server information
	 */

	function FixResult(&$result, $domain)
		{
		// Add usual fields
		$result['regrinfo']['domain']['name'] = $domain;

		// Check if nameservers exist

		if (!isset($result['regrinfo']['registered']))
			{
			if (function_exists('checkdnsrr') && checkdnsrr($domain, 'NS'))
				$result['regrinfo']['registered'] = 'yes';
			else
				$result['regrinfo']['registered'] = 'unknown';
			}

		// Normalize nameserver fields

		if (isset($result['regrinfo']['domain']['nserver']))
			{
			if (!is_array($result['regrinfo']['domain']['nserver']))
				{
				unset($result['regrinfo']['domain']['nserver']);
				}
			else
				$result['regrinfo']['domain']['nserver'] = $this->FixNameServer($result['regrinfo']['domain']['nserver']);
			}
		}
	}

?>
