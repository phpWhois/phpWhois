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

if (!defined('__PL_HANDLER__'))
	define('__PL_HANDLER__', 1);

require_once('whois.parser.php');

class pl_handler
	{

	function parse($data_str, $query)
		{

		$items = array(
                	'owner' 	=> 'Subscribers Contact object:',
					'domain'	=> 'Domain object:',
					'tech'		=> 'Technical Contact:',
					'x'			=> 'nservers:'
					);

		$fields = array (
					'company:'			=> 'organization',
					'street:'			=> 'address.street',
					'city:'				=> 'address.city',
					'location:'			=> 'address.country',
					'handle:'			=> 'handle',
					'created:'			=> 'created',
					'last modified:'	=> 'changed',
					'registrar:'		=> 'sponsor',
					'phone:'			=> 'phone'
					);
					
		$r = get_blocks($data_str['rawdata'], $items);
		
		if (isset($r['tech']))
			{
			if ($r['tech'] == 'data restricted')
				unset($r['tech']);
			else
				$r['tech'] = generic_parser_b($r['tech'], $fields, 'ymd', false);
			}
			
		if (isset($r['owner']))
			{
			if ($r['owner'] == 'data restricted')
				unset($r['owner']);
			else
				$r['owner'] = generic_parser_b($r['owner'], $fields, 'ymd', false);
			
			$r['domain'] = generic_parser_b($r['domain'], $fields, 'ymd', false);
		
			if (isset($r['domain']['handle']))
				{
				$r['owner']['handle'] = $r['domain']['handle'];
				unset($r['domain']['handle']);
				}
				
			// Get name servers
			$found = false;
			$ns = array();
			
			foreach ($data_str['rawdata'] as $line)
				{
				if (substr($line,0,9) == 'nservers:')
					{
					$found = true;
					$ns[] = strtok(trim(substr($line,9)),'[');
					}
				else
					if ($found)
						{
						if (substr($line,0,8) == 'created:')
							break;
						else
							$ns[] = strtok($line,'[');
						}
				}
			
			$r['domain']['nserver'] = $ns;	
			$r['registered'] = 'yes';
			
			$r = array ( 'regrinfo' => $r );
			}
		else
			$r['regrinfo']['registered'] = 'no';

		return ($r);
		}
	}
?>
