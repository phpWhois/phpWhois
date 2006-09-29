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

if (!defined('__RWHOIS_HANDLER__'))
	define('__RWHOIS_HANDLER__', 1);

require_once('whois.parser.php');

class rwhois_handler
	{

	function parse($data_str, $query)
		{

		$items = array(
						'network:Organization-Name:' => 'owner.name',
						'network:Organization;I:' => 'owner.organization',
						'network:Organization-City:' => 'owner.address.city',
						'network:Organization-Zip:' => 'owner.address.pcode',
						'network:Organization-Country:' => 'owner.address.country',
						'network:IP-Network-Block:' => 'network.inetnum',
						'network:IP-Network:' => 'network.inetnum',
						'network:Network-Name:' => 'network.name',
						'network:ID:' => 'network.handle',						
						'network:Created:' => 'network.created',
						'network:Updated:' => 'network.changed',
						'network:Tech-Contact;I:' => 'tech.email',
						'network:Admin-Contact;I:' => 'admin.email'
						);

		$res = generic_parser_b($data_str, $items, 'Ymd', false);
		
		unset($res['disclaimer']);
		return ($res);
		}
	}
?>
