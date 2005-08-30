<?php
/*
Whois.php        PHP classes to conduct whois queries

Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic

Maintained by David Saez (david@ols.es)

For the most recent version of this package visit:

http://phpwhois.sourceforge.net

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

/* ipw.whois	1.00	David Saez 12/07/2001 */
/*              1.01    David Saez 06/07/2002  Added support for */
/*                      BRNIC, KRNIC, TWNIC and LACNIC */

/* Check with 218.165.121.114 (apnic)  */
/*            62.97.102.115   (ripe)   */
/*            207.217.120.54  (arin)   */
/*            200.165.206.74  (brnic)  */
/*            210.178.148.129 (krnic)  */
/*	          200.44.33.31    (lacnic) */

if (!defined('__IP_HANDLER__'))
	define('__IP_HANDLER__', 1);

class ip_handler extends WhoisClient
	{

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
						'whois.registro.br' => 'bripw',
						'whois.lacnic.net' => 'lacnic',
						'whois.afrinic.net' => 'afrinic'
	                     );

	function parse($data, $query)
		{

		$this->Query = array();
		$this->Query['server'] = 'whois.arin.net';
		$this->Query['string'] = $query;

		$result['rawdata'] = array();
		$result['regyinfo'] = array();
		$result['regyinfo']['registrar'] = 'American Registry for Internet Numbers (ARIN)';

		reset($this->REGISTRARS);

		$rawdata = $data['rawdata'];
		$orgname = trim($rawdata[0]);

		if ($orgname == '')
			$orgname = trim($rawdata[1]);

		while (list($string, $whois) = each($this->REGISTRARS))
			if (strstr($orgname, $string) != '')
				{
				$this->Query['server'] = $whois;
				$result['regyinfo']['registrar'] = $string;
				break;
				}

		switch ($this->Query['server'])
			{
			case 'whois.apnic.net':
				$rawdata = $this->GetData($query);
				$rawdata = $rawdata['rawdata'];

				while (list($ln, $line) = each($rawdata))
					{
					if (strstr($line, 'KRNIC whois server at whois.krnic.net'))
						{
						$this->Query['server'] = 'whois.krnic.net';
						$result['regyinfo']['registrar'] = 'Korea Network Information Center (KRNIC)';
						$rawdata = $this->GetData($query);
						$rawdata = $rawdata['rawdata'];
						break;
						}
					}
				break;

			case 'whois.arin.net':
				$newquery = '';

				while (list($ln, $line) = each($rawdata))
					{
					$s = strstr($line, '(NETBLK-');
					if ($s != '')
						{
						$newquery = substr(strtok($s, ') '), 1);
						break;
						}

					$s = strstr($line, '(NET-');

					if ($s != '')
						{
						$newquery = substr(strtok($s, ') '), 1);
						break;
						}
					}

				if ($newquery != "")
					$result['regyinfo']['netname'] = $newquery;

				if (strstr($newquery, 'BRAZIL-BLK'))
					{
					$this->Query["server"] = 'whois.registro.br';
					$result["regyinfo"]["registrar"] = 'Comite Gestor da Internet no Brasil';
					$rawdata = $this->GetData($query);
					$rawdata = $rawdata['rawdata'];
					$newquery = '';
					}

				if ($newquery != '')
					{
					$rawdata = $this->GetData('!'.$newquery);
					$rawdata = $rawdata['rawdata'];
					}
				break;

			case 'whois.lacnic.net':
				$rawdata = $this->GetData($query);
				$rawdata = $rawdata['rawdata'];

				while (list($ln, $line) = each($rawdata))
					{
					$s = strstr($line, 'at whois.registro.br or ');
					if ($s != '')
						{
						$this->Query['server'] = 'whois.registro.br';
						$result['regyinfo']['registrar'] = 'Comite Gestor da Internet do Brazil';
						$rawdata = $this->GetData($query);
						$rawdata = $rawdata['rawdata'];
						break;
						}
					}
				break;

			default:
				$rawdata = $this->GetData($query);
				if (isset($rawdata['rawdata']))
					$rawdata = $rawdata['rawdata'];
			}

		$result['rawdata'] = $rawdata;
		$result['regyinfo']['whois'] = $this->Query['server'];

		if (isset($this->HANDLERS[$this->Query['server']]))
			$this->Query['handler'] = $this->HANDLERS[$this->Query['server']];

		if (!empty($this->Query['handler']))
			{
			$this->Query['file'] = sprintf('whois.ip.%s.php', $this->Query['handler']);
			$result['regrinfo'] = $this->Process($result['rawdata']);
			}

		// Arrange inetnum/cdir
		
		if (isset($result['regrinfo']['network']['inetnum']) && strpos($result['regrinfo']['network']['inetnum'], '/') != false)
			{
			//Convert CDIR to inetnum
			$result['regrinfo']['network']['cdir'] = $result['regrinfo']['network']['inetnum'];
			$result['regrinfo']['network']['inetnum'] = $this->cidr_conv($result['regrinfo']['network']['cdir']);
			}

		if (!isset($result['regrinfo']['network']['inetnum']) && isset($result['regrinfo']['network']['cdir']))
			{
			//Convert CDIR to inetnum
			$result['regrinfo']['network']['inetnum'] = $this->cidr_conv($result['regrinfo']['network']['cdir']);
			}

		// Try to find abuse email address
		
		if (!isset($result['regrinfo']['abuse']['email']))
			{
			reset($result['rawdata']);

			while (list($key, $line) = each($result['rawdata']))
				{
				$email_regex = "/([-_\w\.]+)(@)([-_\w\.]+)\b/i";
								
				if (strpos($line,'abuse') !== false && preg_match($email_regex,$line,$matches)>0)
					{
					$result['regrinfo']['abuse']['email'] = $matches[0];
					break;
					}
				}
			}
			
		//Check if Referral rwhois server has been reported
	
		if (isset($result['regrinfo']['rwhois']))
			{			
			$this->Query['server'] = $result['regrinfo']['rwhois'];
			unset($result['regrinfo']['rwhois']);				
			
			//If so, get customer data from rwhois			
			$this->Query['handler'] = 'rwhois';		
			$this->Query['file'] = 'whois.rwhois.php';

			$rwdata = $this->GetData($query);			
			
			if (!empty($rwdata))
				{			
				$rwres = $this->Process($rwdata);
			
				$result['regrinfo']['customer'] = $rwres;
				$result['regyinfo']['rwhois'] = $this->Query['server'];
				}
			}

		return $result;
		}

	//-----------------------------------------------------------------

	function cidr_conv($net)
		{
		$start = strtok($net, '/');
		$n = 3-substr_count($net, '.');

		if ($n > 0)
			{
			for ($i = $n; $i > 0; $i--)
				$start.= '.0';
			}

		$bits1 = str_pad(decbin(ip2long($start)), 32, '0', 'STR_PAD_LEFT');
		$net = pow(2, (32-substr(strstr($net, '/'), 1))) - 1;
		$bits2 = str_pad(decbin($net), 32, '0', 'STR_PAD_LEFT');
		$final = '';

		for ($i = 0; $i < 32; $i++)
			{
			if ($bits1[$i] == $bits2[$i])
				$final.= $bits1[$i];
			if ($bits1[$i] == 1 and $bits2[$i] == 0)
				$final.= $bits1[$i];
			if ($bits1[$i] == 0 and $bits2[$i] == 1)
				$final.= $bits2[$i];
			}

		return $start." - ".long2ip(bindec($final));
		}

	}
?>
