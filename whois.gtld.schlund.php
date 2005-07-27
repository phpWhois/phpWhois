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

/* schlund.whois  1.00    David Saez <david@ols.es> */

if(!defined("__SCHLUND_HANDLER__")) define("__SCHLUND_HANDLER__",1);

include_once("generic2.whois");

class schlund_handler {

        function parse ($data_str,$query) {

	$items = array(
                        "domain.created" => "created:",
                        "domain.changed" => "last-changed:",
                        "domain.status" => "status:",
                        "owner.name.first" => "registrant-firstname:",
			"owner.name.last" => "registrant-lastname:",
			"owner.organization" => "registrant-organization:",
                        "owner.address.street." => "registrant-street1:",
			"owner.address.street." => "registrant-street2:",
                        "owner.address.pcode" => "registrant-pcode:",
                        "owner.address.city" => "registrant-city:",
                        "owner.address.country" => "registrant-ccode:",
			"owner.phone" => "registrant-phone:",
			"onwer.email" => "registrant-email:",
                        "admin.name.first" => "admin-c-firstname:",
                        "admin.name.last" => "admin-c-lastname:",
                        "admin.organization" => "admin-c-organization:",
                        "admin.address.street." => "admin-c-street1:",
                        "admin.address.street." => "admin-c-street2:",
                        "admin.address.pcode" => "admin-c-pcode:",
                        "admin.address.city" => "admin-c-city:",
                        "admin.address.country" => "admin-c-ccode:",
                        "admin.phone" => "admin-c-phone:",
                        "admin.email" => "admin-c-email:",
			"tech.name.first" => "tech-c-firstname:",
                        "tech.name.last" => "tech-c-lastname:",
                        "tech.organization" => "tech-c-organization:",
                        "tech.address.street." => "tech-c-street1:",
                        "tech.address.street." => "tech-c-street2:",
                        "tech.address.pcode" => "tech-c-pcode:",
                        "tech.address.city" => "tech-c-city:",
                        "tech.address.country" => "tech-c-ccode:",
                        "tech.phone" => "tech-c-phone:",
                        "tech.email" => "tech-c-email:",
			"billing.name.first" => "bill-c-firstname:",
                        "billing.name.last" => "bill-c-lastname:",
                        "billing.organization" => "bill-c-organization:",
                        "billing.address.street." => "bill-c-street1:",
                        "billing.address.street." => "bill-c-street2:",
                        "billing.address.pcode" => "bill-c-pcode:",
                        "billing.address.city" => "bill-c-city:",
                        "billing.address.country" => "bill-c-ccode:",
                        "billing.phone" => "bill-c-phone:",
                        "billing.email" => "bill-c-email:"
			);

	$r=generic_whois($data_str,$items);
	return($r);
	}
}
?>
