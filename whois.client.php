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

require_once('whois.ip.lib.php');

class WhoisClient {
	
	// Recursion allowed ?
	var $gtld_recurse = false;

	// Default WHOIS port
	var $PORT = 43;

	// Maximum number of retries on connection failure
	var $RETRY = 0;

	// Time to wait between retries
	var $SLEEP = 2;

	// Read buffer size (0 == char by char)
	var $BUFFER = 1024;
	
	// Communications timeout
	var $STIMEOUT = 10;

	// List of servers and handlers (loaded from servers.whois)
	var $DATA = array(); 	
	
	// Array to contain all query variables
	var $Query = array(
		'tld' => '',
		'type' => 'domain',
		'query' => '',
		'status',
		'server'
		);

	// This release of the package
	var $CODE_VERSION = '4.2.2';
	
	// Full code and data version string (e.g. 'Whois2.php v3.01:16')
	var $VERSION;
	
	/*
	 * Constructor function
	 */
	function WhoisClient () {
		// Load DATA array
		@require('whois.servers.php');		

		// Set version
		$this->VERSION = sprintf("phpWhois v%s-%s", $this->CODE_VERSION, $this->DATA_VERSION);
	}
		
	/*
	 * Perform lookup
	 */

	function GetRawData ($query) {
		
		$this->Query['query'] = $query;
		
		// clear error description
		if (isset($this->Query['errstr'])) unset($this->Query['errstr']);
		
		if (!isset($this->Query['server'])) {
			$this->Query['status'] = 'error';
			$this->Query['errstr'][] = 'No server specified';
			return(array());
			}

		// Check if protocol is http
		
		if (substr($this->Query['server'],0,7)=='http://' ||
			substr($this->Query['server'],0,8)=='https://')
			{
			$output = $this->httpQuery($this->Query['server']);
			
			if (!$output)
				{
				$this->Query['status'] = 'error';
				$this->Query['errstr'][] = 'Connect failed to: '.$this->Query['server'];
				return(array());
				}
				
			$this->Query['args'] = substr(strchr($this->Query['server'],'?'),1);
			$this->Query['server'] = strtok($this->Query['server'],'?');
			
			if (substr($this->Query['server'],0,7)=='http://')
				$this->Query['server_port'] = 80;
			else
				$this->Query['server_port'] = 483;
			}
		else
			{
			// Get args
			
			if (strpos($this->Query['server'],'?'))
				{
				$parts = explode('?',$this->Query['server']);
				$this->Query['server'] = trim($parts[0]);
				$query_args = trim($parts[1]);
				
				// replace substitution parameters			
				$query_args = str_replace('{query}', $query, $query_args);
				$query_args = str_replace('{version}', 'phpWhois'.$this->CODE_VERSION, $query_args);
				
				if (strpos($query_args,'{ip}')!==false)
					{
					$query_args = str_replace('{ip}', phpwhois_getclientip(), $query_args);
					}
					
				if (strpos($query_args,'{hname}')!==false)
					{
					$query_args = str_replace('{hname}', gethostbyaddr(phpwhois_getclientip()), $query_args);
					}
				}
			else
				{
				if (empty($this->Query['args']))
					$query_args = $query;
				else
					$query_args = $this->Query['args'];
				}

			$this->Query['args'] = $query_args;

			if (substr($this->Query['server'],0,9) == 'rwhois://')
				{
				$this->Query['server'] = substr($this->Query['server'],9);
				}

			if (substr($this->Query['server'],0,8) == 'whois://')
				{
				$this->Query['server'] = substr($this->Query['server'],8);
				}
			
			// Get port
			
			if (strpos($this->Query['server'],':'))
				{
				$parts = explode(':',$this->Query['server']);
				$this->Query['server'] = trim($parts[0]);
				$this->Query['server_port'] = trim($parts[1]);
				}
			else			
				$this->Query['server_port'] = $this->PORT;
				
			// Connect to whois server, or return if failed

			$ptr = $this->Connect();

			if($ptr < 0) {
				$this->Query['status'] = 'error';
				$this->Query['errstr'][] = 'Connect failed to: '.$this->Query['server'];
				return array();
				}

			stream_set_timeout($ptr,$this->STIMEOUT);
			stream_set_blocking($ptr,0);
			
			// Send query
			fputs($ptr, trim($query_args)."\r\n");
			
			// Prepare to receive result
			$raw = '';
			$start = time();
			$null = NULL;
			$r = array($ptr);

			while (!feof($ptr))
				{
				if (stream_select($r,$null,$null,$this->STIMEOUT))
					{
					$raw .= fgets($ptr, $this->BUFFER);
					}

				if (time()-$start > $this->STIMEOUT)
					{
					$this->Query['status'] = 'error';
					$this->Query['errstr'][] = 'Timeout reading from '.$this->Query['server'];
					return array();
					}
				}

			if (array_key_exists($this->Query['server'],$this->NON_UTF8))
				{
				$raw = utf8_encode($raw);
				}

			$output = explode("\n", $raw);

			// Drop empty last line (if it's empty! - saleck)
			if (empty($output[count($output)-1]))
				unset($output[count($output)-1]);
			}
		
		return $output;	
	}

	/*
	 * Perform lookup. Returns an array. The 'rawdata' element contains an
	 * array of lines gathered from the whois query. If a top level domain
	 * handler class was found for the domain, other elements will have been
	 * populated too.
	 */

	function GetData ($query='', $deep_whois=true) {
	
		// If domain to query passed in, use it, otherwise use domain from initialisation
		$query = !empty($query) ? $query : $this->Query['query'];
				
		$output = $this->GetRawData($query);
						
		// Create result and set 'rawdata'
		$result = array( 'rawdata' => $output );		
		$result = $this->set_whois_info($result);

		// Return now on error
		if (empty($output)) return $result;
		
		// If we have a handler, post-process it with it
		if (isSet($this->Query['handler']))
			{
			// Keep server list
			$servers = $result['regyinfo']['servers'];
			unset($result['regyinfo']['servers']);
			
			// Process data
			$result = $this->Process($result,$deep_whois);
		
			// Add new servers to the server list
			if (isset($result['regyinfo']['servers']))
				$result['regyinfo']['servers'] = array_merge($servers,$result['regyinfo']['servers']);
			else
				$result['regyinfo']['servers'] = $servers;
			
			// Handler may forget to set rawdata
			if (!isset($result['rawdata']))
				$result['rawdata'] = $output;
			}

		// Type defaults to domain
		if (!isset($result['regyinfo']['type']))
			$result['regyinfo']['type'] = 'domain';	
		
		// Add error information if any
		if (isset($this->Query['errstr']))
			$result['errstr'] = $this->Query['errstr'];

		// Fix/add nameserver information
		if (method_exists($this,'FixResult') && $this->Query['tld'] != 'ip')
			$this->FixResult($result,$query);
			
		return($result);
	}
	
	/*
	*   Adds whois server query information to result
	*/
	
	function set_whois_info ($result)
		{
		$info = array(
					'server'=> $this->Query['server'],
					);

		if (!empty($this->Query['args']))
			$info['args'] = $this->Query['args'];
		else
			$info['args'] = $this->Query['query'];
		
		if (!empty($this->Query['server_port']))
			$info['port'] = $this->Query['server_port'];
		else
			$info['port'] = 43;
			
		if (isset($result['regyinfo']['whois']))
			unset($result['regyinfo']['whois']);
		
		if (isset($result['regyinfo']['rwhois']))
			unset($result['regyinfo']['rwhois']);
			
		$result['regyinfo']['servers'][] = $info;
		
		return $result;
		}

	/*
	*   Convert html output to plain text
	*/
	function httpQuery ($query) {
		
		//echo ini_get('allow_url_fopen');
		
		//if (ini_get('allow_url_fopen'))
			$lines = @file($this->Query['server']);		
			
		if (!$lines) return false;
		
		$output = '';
		$pre = '';

		while (list($key, $val)=each($lines)) {
			$val = trim($val);

			$pos=strpos(strtoupper($val),'<PRE>');
			if ($pos!==false) {
				$pre = "\n";
				$output.=substr($val,0,$pos)."\n";
				$val = substr($val,$pos+5);
				}
			$pos=strpos(strtoupper($val),'</PRE>');
			if ($pos!==false) {
				$pre = '';
				$output.=substr($val,0,$pos)."\n";
				$val = substr($val,$pos+6);
				}
			$output.=$val.$pre;
			}
			
		$search = array (
				'<BR>', '<P>', '</TITLE>',
				'</H1>', '</H2>', '</H3>',
				'<br>', '<p>', '</title>',
				'</h1>', '</h2>', '</h3>'  );

		$output = str_replace($search,"\n",$output);
		$output = str_replace('<TD',' <td',$output);
		$output = str_replace('<td',' <td',$output);
		$output = str_replace('<tr',"\n<tr",$output);
		$output = str_replace('<TR',"\n<tr",$output);
		$output = str_replace('&nbsp;',' ',$output);
		$output = strip_tags($output);		
		$output = explode("\n",$output);

		$rawdata = array();
		$null = 0;

		while (list($key, $val)=each($output)) {
			$val=trim($val);
			if ($val=='') {
				if (++$null>2) continue;
			}
			else $null=0;
			$rawdata[]=$val;
		}
		return $rawdata;
	}
	
	/*
	 * Open a socket to the whois server.
	 *
	 * Returns a socket connection pointer on success, or -1 on failure.
	 */
	function Connect ($server = '') {
	
		if ($server == '')
			$server = $this->Query['server'];
			
		// Fail if server not set
		if($server == '')
			return(-1);

		// Get rid of protocol and/or get port
		$port = $this->Query['server_port'];
		
		$pos = strpos($server,'://');
		
		if ($pos !== false)
			$server = substr($server, $pos+3);
			
		$pos = strpos($server,':');
		
		if ($pos !== false)
			{
			$port = substr($server,$pos+1);
			$server = substr($server,0,$pos);			
			}
			
		// Enter connection attempt loop
		$retry = 0;
		
		while($retry <= $this->RETRY) {
			// Set query status
			$this->Query['status'] = 'ready';

			// Connect to whois port
			$ptr = @fsockopen($server, $port, $errno, $errstr, $this->STIMEOUT);
			
			if($ptr > 0) {
				$this->Query['status'] = 'ok';
				return($ptr);
			}
			
			// Failed this attempt
			$this->Query['status'] = 'error';
			$this->Query['error'][] = $errstr;
			$retry++;

			// Sleep before retrying
			sleep($this->SLEEP);
		}
		
		// If we get this far, it hasn't worked
		return(-1);
	} 	
	
	/*
	 * Post-process result with handler class. On success, returns the result
	 * from the handler. On failure, returns passed result unaltered.
	 */
	function Process (&$result, $deep_whois=true) {

		$handler_name = str_replace('.','_',$this->Query['handler']);

		// If the handler has not already been included somehow, include it now
		$HANDLER_FLAG = sprintf("__%s_HANDLER__", strtoupper($handler_name));

		if (!defined($HANDLER_FLAG))
			include($this->Query['file']);

		// If the handler has still not been included, append to query errors list and return
		if (!defined($HANDLER_FLAG))
			{
			$this->Query['errstr'][] = "Can't find $handler_name handler: ".$this->Query['file'];
			return($result);
			}

		if (!$this->gtld_recurse && $this->Query['file'] == 'whois.gtld.php')
			return $result;

		// Pass result to handler
		$object = $handler_name.'_handler';
		
		$handler = new $object('');

		// If handler returned an error, append it to the query errors list
		if(isSet($handler->Query['errstr']))
			$this->Query['errstr'][] = $handler->Query['errstr'];

		$handler->deep_whois = $deep_whois;

		// Process
		$res = $handler->parse($result,$this->Query['query']);

		// Return the result
		return $res;
	}	
	
	/*
	 * Does more (deeper) whois ...
	 */
	 
	function DeepWhois ($query, $result) {
	
		if (!isset($result['regyinfo']['whois'])) return $result;
		
		$this->Query['server'] = $wserver = $result['regyinfo']['whois'];
		unset($result['regyinfo']['whois']);
		$subresult = $this->GetRawData($query);

		if (!empty($subresult))
			{
			$result = $this->set_whois_info($result);
			$result['rawdata'] = $subresult;
		
			if (isset($this->WHOIS_GTLD_HANDLER[$wserver]))
				$this->Query['handler'] = $this->WHOIS_GTLD_HANDLER[$wserver];
			else
				{
				$parts = explode('.',$wserver);
				$hname = strtolower($parts[1]);

				if (($fp = @fopen('whois.gtld.'.$hname.'.php', 'r', 1)) and fclose($fp))
					$this->Query['handler'] = $hname;
				}
				
			if (!empty($this->Query['handler']))
				{			
				$this->Query['file'] = sprintf('whois.gtld.%s.php', $this->Query['handler']);
				$regrinfo = $this->Process($subresult); //$result['rawdata']);
				$result['regrinfo'] = $this->merge_results($result['regrinfo'], $regrinfo);
				//$result['rawdata'] = $subresult;
				}
			}
				
		return $result;
	}
	
	/*
	 *  Merge results
	 */
	 
	function merge_results($a1, $a2) {

		reset($a2);
	
		while (list($key, $val) = each($a2))
			{
			if (isset($a1[$key]))
				{
				if (is_array($val))
					{
					if ($key != 'nserver')
						$a1[$key] = $this->merge_results($a1[$key], $val);
					}
				else
					{
					$val = trim($val);
					if ($val != '')
						$a1[$key] = $val;
					}
				}
			else
				$a1[$key] = $val;
			}
	
		return $a1;
	}
	
	function FixNameServer($nserver)
		{
		$dns = array();

		foreach($nserver as $val)
			{
			$val = str_replace( array('[',']','(',')'), '', trim($val));
			$val = str_replace("\t", ' ', $val);
			$parts = explode(' ', $val);
			$host = '';
			$ip = '';

			foreach($parts as $p)
				{
				if (substr($p,-1) == '.') $p = substr($p,0,-1);

				if ((ip2long($p) == - 1) or (ip2long($p) === false))
					{
					// Hostname ?
					if ($host == '' && preg_match('/^[\w\-]+(\.[\w\-]+)+$/',$p))
						{
						$host = $p;
						}
					}
				else
					// IP Address
					$ip = $p;
				}

			// Valid host name ?

			if ($host == '') continue;

			// Get ip address

			if ($ip == '')
				{
				$ip = gethostbyname($host);
				if ($ip == $host) $ip = '(DOES NOT EXIST)';
				}

			if (substr($host,-1,1) == '.') $host = substr($host,0,-1);
				
			$dns[strtolower($host)] = $ip;
			}

		return $dns;
		}
}
?>