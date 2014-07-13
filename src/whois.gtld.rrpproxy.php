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

if (!defined('__RRPPROXY_HANDLER__'))
	define('__RRPPROXY_HANDLER__', 1);

require_once('whois.parser.php');

class rrpproxy_handler
	{
	function parse($data_str, $query)
		{
		$items = array(
                  'created-date:' => 'domain.created',
                  'updated-date:' => 'domain.changed',
                  'registration-expiration-date:' => 'domain.expires',
                  'RSP:' => 'domain.sponsor',
                  'URL:' => 'domain.referrer',
                  'owner-nom.contact:' => 'owner.handle',
                  'owner-fname:' => 'owner.name.first',
                  'owner-lname:' => 'owner.name.last',
                  'owner-organization:' => 'owner.organization',
                  'owner-street:' => 'owner.address.street',
                  'owner-city:' => 'owner.address.city',
                  'owner-zip:' => 'owner.address.pcode',
                  'owner-country:' => 'owner.address.country',
                  'owner-phone:' => 'owner.phone',
                  'owner-fax:' => 'owner.fax',
                  'owner-email:' => 'owner.email',
                  'admin-nom.contact:' => 'admin.handle',
                  'admin-fname:' => 'admin.name.first',
                  'admin-lname:' => 'admin.name.last',
                  'admin-organization:' => 'admin.organization',
                  'admin-street:' => 'admin.address.street',
                  'admin-city:' => 'admin.address.city',
                  'admin-zip:' => 'admin.address.pcode',
                  'admin-country:' => 'admin.address.country',
                  'admin-phone:' => 'admin.phone',
                  'admin-fax:' => 'admin.fax',
                  'admin-email:' => 'admin.email',
                  'tech-nom.contact:' => 'tech.handle',
                  'tech-fname:' => 'tech.name.first',
                  'tech-lname:' => 'tech.name.last',
                  'tech-organization:' => 'tech.organization',
                  'tech-street:' => 'tech.address.street',
                  'tech-city:' => 'tech.address.city',
                  'tech-zip:' => 'tech.address.pcode',
                  'tech-country:' => 'tech.address.country',
                  'tech-phone:' => 'tech.phone',
                  'tech-fax:' => 'tech.fax',
                  'tech-email:' => 'tech.email',
                  'billing-nom.contact:' => 'billing.handle',
                  'billing-fname:' => 'billing.name.first',
                  'billing-lname:' => 'billing.name.last',
                  'billing-organization:' => 'billing.organization',
                  'billing-street:' => 'billing.address.street',
                  'billing-city:' => 'billing.address.city',
                  'billing-zip:' => 'billing.address.pcode',
                  'billing-country:' => 'billing.address.country',
                  'billing-phone:' => 'billing.phone',
                  'billing-fax:' => 'billing.fax',
                  'billing-email:' => 'billing.email'
		              );

		return generic_parser_b($data_str, $items);
		}
	}
?>