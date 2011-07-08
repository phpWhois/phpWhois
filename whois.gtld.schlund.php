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

if (!defined('__SCHLUND_HANDLER__'))
	define('__SCHLUND_HANDLER__', 1);

require_once('whois.parser.php');

class schlund_handler
	{
	function parse($data_str, $query)
		{
		$items = array(
                  'created:' => 'domain.created',
                  'last-changed:' => 'domain.changed',
                  'status:' => 'domain.status',
                  'registrant-firstname:' => 'owner.name.first',
                  'registrant-lastname:' => 'owner.name.last',
                  'registrant-organization:' => 'owner.organization',
                  'registrant-street1:' => 'owner.address.street.',
                  'registrant-street2:' => 'owner.address.street.',
                  'registrant-pcode:' => 'owner.address.pcode',
                  'registrant-city:' => 'owner.address.city',
                  'registrant-ccode:' => 'owner.address.country',
                  'registrant-phone:' => 'owner.phone',
                  'registrant-email:' => 'owner.email',
                  'admin-c-firstname:' => 'admin.name.first',
                  'admin-c-lastname:' => 'admin.name.last',
                  'admin-c-organization:' => 'admin.organization',
                  'admin-c-street1:' => 'admin.address.street.',
                  'admin-c-street2:' => 'admin.address.street.',
                  'admin-c-pcode:' => 'admin.address.pcode',
                  'admin-c-city:' => 'admin.address.city',
                  'admin-c-ccode:' => 'admin.address.country',
                  'admin-c-phone:' => 'admin.phone',
                  'admin-c-email:' => 'admin.email',
                  'tech-c-firstname:' => 'tech.name.first',
                  'tech-c-lastname:' => 'tech.name.last',
                  'tech-c-organization:' => 'tech.organization',
                  'tech-c-street1:' => 'tech.address.street.',
                  'tech-c-street2:' => 'tech.address.street.',
                  'tech-c-pcode:' => 'tech.address.pcode',
                  'tech-c-city:' => 'tech.address.city',
                  'tech-c-ccode:' => 'tech.address.country',
                  'tech-c-phone:' => 'tech.phone',
                  'tech-c-email:' => 'tech.email',
                  'bill-c-firstname:' => 'billing.name.first',
                  'bill-c-lastname:' => 'billing.name.last',
                  'bill-c-organization:' => 'billing.organization',
                  'bill-c-street1:' => 'billing.address.street.',
                  'bill-c-street2:' => 'billing.address.street.',
                  'bill-c-pcode:' => 'billing.address.pcode',
                  'bill-c-city:' => 'billing.address.city',
                  'bill-c-ccode:' => 'billing.address.country',
                  'bill-c-phone:' => 'billing.phone',
                  'bill-c-email:' => 'billing.email'
		              );

		return generic_parser_b($data_str, $items);
		}
	}
?>