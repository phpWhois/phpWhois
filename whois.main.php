<?php
/*
Whois.php        PHP classes to conduct whois queries

Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic

Maintained by David Saez (david@ols.es)

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

class Whois extends WhoisClient
	{
	// Windows based ?
	var $windows = false;

	// Recursion allowed ?
	var $gtld_recurse = true;

	// Full code and data version string (e.g. 'Whois2.php v3.01:16')
	var $VERSION;

	// This release of the package
	var $CODE_VERSION = "4.0.1";

	// Network Solutions registry server
	var $NSI_REGISTRY = "whois.nsiregistry.net";

	/*
	 * Constructor function
	 */
	function Whois()
		{
		// Load DATA array
		@require('whois.servers.php');

		$pos = strpos(strtolower(getenv("OS")), "win");

		if ($pos == false)
			$this->windows = false;
		else
			$this->windows = true;

		// Set version
		$this->VERSION = sprintf("Whois.php v%s:%s", $this->CODE_VERSION, $this->DATA_VERSION);
		}


	function Lookup($query = '')
		{
		// start clean
		$this->Query['status'] = 0;
		
		$query = trim($query);

		// If domain to query was not set
		if (!isSet($query) || $query == '')
			{
			// Configure to use default whois server
			$this->Query['server'] = $this->NSI_REGISTRY;
			return ;
			}

		// Set domain to query in query array

		$this->Query['string'] = $domain = strtolower($query);

		// If query is an ip address do ip lookup

		if ($query == long2ip(ip2long($query)) || !strpos($query, '.'))
			{
			// Prepare to do lookup via the 'ip' handler
			$ip = @gethostbyname($query);
			$this->Query['server'] = 'whois.arin.net';
			$this->Query['host_ip'] = $ip;
			$this->Query['file'] = 'whois.ip.php';
			$this->Query['handler'] = 'ip';
			$this->Query['string'] = $ip;
			$this->Query['tld'] = 'ip';
			$this->Query['host_name'] = @gethostbyaddr($ip);
			return $this->GetData();
			}

		// Build array of all possible tld's for that domain

		$tld = '';
		$server = '';
		$dp = explode('.', $domain);
		$np = count($dp) - 1;
		$tldtests = array();

		for ($i = 0; $i < $np; $i++)
			{
			array_shift($dp);
			$tldtests[] = implode('.', $dp);
			}

		// Search the correct whois server

		foreach($tldtests as $tld)
			{
			// Test if we know in advance that no whois server is
			// available for this domain and that we can get the
			// data via http or whois request
/*
			reset($this->WHOIS_SPECIAL);

			while (list($key, $val) = each($this->WHOIS_SPECIAL))
				if ($tld == $key)
					{
					$domain = substr($query, 0,  - strlen($key) - 1);
					$val = str_replace('{domain}', $domain, $val);
					$server = str_replace('{tld}', $key, $val);
					break;
					}
			if ($server != '')
				break;				
*/
			if (isset($this->WHOIS_SPECIAL[$tld]))
				{
				$val = $this->WHOIS_SPECIAL[$tld];
				$domain = substr($query, 0,  - strlen($tld) - 1);
				$val = str_replace('{domain}', $domain, $val);
				$server = str_replace('{tld}', $tld, $val);
				break;
				}
				
			// Determine the top level domain, and it's whois server using
			// DNS lookups on 'whois-servers.net'.
			// Assumes a valid DNS response indicates a recognised tld (!?)

			if ($this->windows)
				$cname = $this->checkdnsrr_win($tld.'.whois-servers.net', 'CNAME');
			else
				$cname = checkdnsrr($tld.'.whois-servers.net', 'CNAME');

			if (!$cname)
				continue;
			//This also works
			//$server = gethostbyname($tld.".whois-servers.net");
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
				}
				
			// If there is a handler process the data

			if ($handler != '')
				{
				$this->Query['file'] = "whois.$handler.php";
				$this->Query['handler'] = $handler;
				}

			return $this->GetData();
			}

		// If tld not known, and domain not in DNS, return error
		unset($this->Query['server']);
		$this->Query['status'] =  - 1;
		$this->Query['errstr'][] = $this->Query['string'].' domain is not supported';
		return ;
		}

	/*
	 *  Checks dns reverse records on win platform
	 */

	function checkdnsrr_win($hostName, $recType = '')
		{
		if (!empty($hostName))
			{
			if ($recType == '')
				$recType = "MX";
			exec("nslookup -type=$recType $hostName", $result);
			// check each line to find the one that starts with the host
			// name. If it exists thenthe function succeeded.
			foreach($result as $line)
				{
				if (eregi("^$hostName", $line))
					return true;
				}
			// otherwise there was no mail handler for the domain
			return false;
			}
		return false;
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
			if ($this->windows)
				$has_ns = $this->checkdnsrr_win($domain, "NS");
			else
				$has_ns = checkdnsrr($domain, "NS");

			if ($has_ns)
				$result['regrinfo']['registered'] = 'yes';
			else
				$result['regrinfo']['registered'] = 'unknown';
			}

		if (!isset($result['regrinfo']['domain']['nserver']))
			return ;

		// Normalize nameserver fields
		$nserver = $result['regrinfo']['domain']['nserver'];

		if (!is_array($result['regrinfo']['domain']['nserver']))
			{
			unset($result['regrinfo']['domain']['nserver']);
			return ;
			}

		$dns = array();

		while (list($key, $val) = each($nserver))
			{
			$val = str_replace('[', '', trim($val));
			$val = str_replace(']', '', $val);
			$val = str_replace("\t", ' ', $val);
			$parts = explode(' ', $val);
			$host = '';
			$ip = '';

			while (list($k, $p) = each($parts))
				{
				if ($p == '')
					continue;
				if (ip2long($p) == - 1)
					{
					if ($host == '')
						$host = $p;
					}
				else
					$ip = $p;
				}
			if ($ip == '')
				{
				$ip = gethostbyname($host);
				if ($ip == $host)
					$ip = '(DOES NOT EXIST)';
				}

			if (substr($host,-1,1) == '.')
				$host = substr($host,0,-1);
				
			$dns[strtolower($host)] = $ip;
			}

		$result['regrinfo']['domain']['nserver'] = $dns;
		}
	}

?>
