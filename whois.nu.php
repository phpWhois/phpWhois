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

if (!defined('__NU_HANDLER__'))
	define('__NU_HANDLER__', 1);

require_once('whois.parser.php');

class nu_handler
	{
	function parse($data_str, $query)
		{
		$items = array(
                  'name' => 'Domain Name (UTF-8):',
                  'created' => 'Record created on',
                  'expires' => 'Record expires on',
                  'changed' => 'Record last updated on',
                  'status' => 'Record status:',
                  'handle' => 'Record ID:'
		              );

		while (list($key, $val) = each($data_str['rawdata']))
			{
			$val = trim($val);

			if ($val != '')
				{
				if ($val == 'Domain servers in listed order:')
					{
					while (list($key, $val) = each($data_str['rawdata']))
						{
						$val = trim($val);
						if ($val == '')
							break;
						$r['regrinfo']['domain']['nserver'][] = $val;
						}
					break;
					}

				reset($items);

				while (list($field, $match) = each($items))
				if (strstr($val, $match))
					{
					$r['regrinfo']['domain'][$field] = trim(substr($val, strlen($match)));
					break;
					}
				}
			}

		if (isset($r['regrinfo']['domain']))
			$r['regrinfo']['registered'] = 'yes';
		else
			$r['regrinfo']['registered'] = 'no';

		$r['regyinfo'] = array(
                          'whois' => 'whois.nic.nu',
                          'referrer' => 'http://www.nunames.nu',
                          'registrar' => '.NU Domain, Ltd'
		                      );

		format_dates($r, 'dmy');
		return $r;
		}
	}
?>