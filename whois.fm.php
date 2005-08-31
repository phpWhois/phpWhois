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

/* dotfm.whois    1.0    David Saez 4/4/2003 */

if (!defined("__FM_HANDLER__"))
	define("__FM_HANDLER__", 1);

require_once('whois.parser.php');

class fm_handler
	{

	function parse($data, $query)
		{

		$items = array(
                  "owner" => "Registrant",
                  "admin" => "Administrative",
                  "tech" => "Technical",
                  "billing" => "Billing"
                  );

		$blocks = get_blocks($data['rawdata'], $items);

		$items = array(
                  "FM Domain:" => "name",
                  "Primary Hostname:" => "nserver.0",
                  "Secondary Hostname:" => "nserver.1",
                  "Renewal Date:" => "expires"
		              );

		$r['regrinfo']['domain'] = generic_parser_b($data['rawdata'], $items);

		$items = array(
                'Organiztion:' => 'organization',
                'Name:' => 'name',
 				'Address:' => 'address.0',
                'City, State Zip:' => 'address.1',
 				'Country:' => 'address.country',
                'Phone:' => 'phone',
                'Fax:' => 'fax',
                'Email:' => 'email'
		            );

		$r['rawdata'] = $data['rawdata'];

		while (list($key, $val) = each($blocks))
			{
			$r['regrinfo'][$key] = generic_parser_b($val, $items);
			}

		$r['regyinfo']['referrer'] = 'http://www.dot.dm';
		$r['regyinfo']['registrar'] = 'dotFM';

		return ($r);
		}
	}
?>
