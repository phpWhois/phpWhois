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

if (!defined('__LT_HANDLER__'))
	define('__LT_HANDLER__', 1);

require_once('whois.parser.php');

class lt_handler
	{

	function parse($data_str, $query)
		{

		$translate = array(
					'e-mail' => 'email',
					'nic-hdl' => 'handle',
					'person' => 'name'
					);

		$contacts = array(
                    'admin-c' => 'admin',
                    'tech-c' => 'tech',
                    'zone-c' => 'zone'
		                );

		$r['regyinfo'] = array(
                    'referrer' => 'http://www.domreg.lt',
                    'registrar' => 'DOMREG.LT'
                    );

		$reg = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');
		
		if (isset($reg['domain']['holder/descr']))
			{
			$owner = $reg['domain']['holder/descr'];
			$reg['owner']['organization'] = $owner[0];
			array_shift($owner);
			$reg['owner']['address'] = $owner;
			unset($reg['domain']['holder/descr']);
			}
/*
		if (isset($reg["domain"]["remarks"]))
			unset($reg["domain"]["remarks"]);

		if (isset($reg["domain"]["descr"]))
			{
			while (list($key, $val) = each($reg["domain"]["descr"]))
				{
				$v = trim(substr(strstr($val, ":"), 1));
				if (strstr($val, "[organization]:"))
					{
					$reg["owner"]["organization"] = $v;
					continue;
					}
				if (strstr($val, "[phone]:"))
					{
					$reg["owner"]["phone"] = $v;
					continue;
					}
				if (strstr($val, "[fax-no]:"))
					{
					$reg["owner"]["fax"] = $v;
					continue;
					}
				if (strstr($val, "[e-mail]:"))
					{
					$reg["owner"]["email"] = $v;
					continue;
					}

				$reg["owner"]["address"][$key] = $v;
				}

			unset($reg["domain"]["descr"]);
			}
*/
		$r['regrinfo'] = $reg;
		return ($r);
		}
	}
?>
