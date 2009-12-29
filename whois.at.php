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

/* atnic.whois  2.00    David Saez <david@ols.es> */
/* atnic.whois	0.99	Martin Pircher <martin@pircher.net> */
/* dedicated to klopfer, *24.07.1999, +21.01.2001         */
/* based upon brnic.whois by Marcelo Sanches  msanches@sitebox.com.br */

if (!defined('__AT_HANDLER__'))
	define('__AT_HANDLER__', 1);

require_once('whois.parser.php');

class at_handler
	{

	function parse($data_str, $query)
		{
		$translate = array(
			'fax-no' => 'fax',
			'e-mail' => 'email',
			'nic-hdl' => 'handle',
			'person' => 'name',
			'personname' => 'name',
			'street address' => 'address.street',
			'city' =>	'address.city',
			'postal code' => 'address.pcode',
			'country' => 'address.country'
			);

		$contacts = array(
                    'owner-c' => 'owner',
                    'admin-c' => 'admin',
                    'tech-c' => 'tech',
                    'billing-c' => 'billing',
                    'zone-c' => 'zone'
		                );

		$r['regyinfo'] = array(
                    'referrer' => 'http://www.nic.at',
                    'registrar' => 'NIC-AT'
                    );

		$reg = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');

		if (isset($reg['domain']['remarks']))
			unset($reg['domain']['remarks']);

		if (isset($reg['domain']['descr']))
			{
			while (list($key, $val) = each($reg['domain']['descr']))
				{
				$v = trim(substr(strstr($val, ':'), 1));
				if (strstr($val, '[organization]:'))
					{
					$reg['owner']['organization'] = $v;
					continue;
					}
				if (strstr($val, '[phone]:'))
					{
					$reg['owner']['phone'] = $v;
					continue;
					}
				if (strstr($val, '[fax-no]:'))
					{
					$reg['owner']['fax'] = $v;
					continue;
					}
				if (strstr($val, '[e-mail]:'))
					{
					$reg['owner']['email'] = $v;
					continue;
					}

				$reg['owner']['address'][$key] = $v;
				}

			if (isset($reg['domain']['descr'])) unset($reg['domain']['descr']);
			}

		$r['regrinfo'] = $reg;
		return ($r);
		}
	}
?>
