<?
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

/* joker.whois  1.10    David Saez <david@ols.es> */

if(!defined("__JOKER_HANDLER__")) define("__JOKER_HANDLER__",1);

require_once('whois.parser.php');

class joker_handler {

        function parse ($data_str,$query) {

	$items = array(
                        'owner.name' => 'owner:',
                        'owner.address.street' => 'address:',
                        'owner.address.zcode' => 'postal-code:',
                        'owner.address.city' => 'city:',
                        'owner.address.state' => 'state:',
                        'owner.address.country' => 'country:',
			'admin.email' => 'admin-c:',
			'tech.email' => 'tech-c:',
			'billing.email' => 'billing-c:',
			'domain.created' => 'created:',
			'domain.changed' => 'modified:',
			'domain.sponsor' => 'reseller-1:'
			);

	$r=generic_parser_b($data_str,$items);
	return($r);
	}
}
?>
