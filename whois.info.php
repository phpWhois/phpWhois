<?php
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

/* info.whois   2.0  David Seaz - updated to common object model */
/* info.whois	1.0  David Saez Padros <david@ols.es> */

if (!defined("__INFO_HANDLER__"))
	define("__INFO_HANDLER__", 1);

require_once('whois.parser.php');

class info_handler
	{

	function parse($data_str, $query)
		{

		$items = array(
                  "domain.handle" => "Domain ID:",
                  "domain.name" => "Domain Name:",
                  "domain.created" => "Created On:",
                  "domain.changed" => "Last Updated On:",
                  "domain.expires" => "Expiration Date:",
                  "domain.sponsor" => "Sponsoring Registrar:",
                  "domain.status" => "Status:",
                  "domain.nserver." => "Name Server:",
                  "owner.handle" => "Registrant ID:",
                  "owner.name" => "Registrant Name:",
                  "owner.organization" => "Registrant Organization:",
                  "owner.address.street.0" => "Registrant Street1:",
                  "owner.address.street.1" => "Registrant Street2:",
                  "owner.address.city" => "Registrant City:",
                  "owner.address.state" => "Registrant State/Province:",
                  "owner.address.pcode" => "Registrant Postal Code:",
                  "owner.address.country" => "Registrant Country:",
                  "owner.phone" => "Registrant Phone:",
                  "owner.fax" => "Registrant FAX:",
                  "owner.email" => "Registrant Email:",
                  "admin.handle" => "Admin ID:",
                  "admin.name" => "Admin Name:",
                  "admin.organization" => "Admin Organization:",
                  "admin.address.street.0" => "Admin Street1:",
                  "admin.address.street.1" => "Admin Street2:",
                  "admin.address.city" => "Admin City:",
                  "admin.address.state" => "Admin State/Province:",
                  "admin.address.pcode" => "Admin Postal Code:",
                  "admin.address.country" => "Admin Country:",
                  "admin.phone" => "Admin Phone:",
                  "admin.fax" => "Admin FAX:",
                  "admin.email" => "Admin Email:",
                  "tech.handle" => "Tech ID:",
                  "tech.name" => "Tech Name:",
                  "tech.organization" => "Tech Organization:",
                  "tech.address.street.0" => "Tech Street1:",
                  "tech.address.street.1" => "Tech Street2:",
                  "tech.address.city" => "Tech City:",
                  "tech.address.state" => "Tech State/Province:",
                  "tech.address.pcode" => "Tech Postal Code:",
                  "tech.address.country" => "Tech Country:",
                  "tech.phone" => "Tech Phone:",
                  "tech.fax" => "Tech FAX:",
                  "tech.email" => "Tech Email:",
                  "billing.handle" => "Billing ID:",
                  "billing.name" => "Billing Name:",
                  "billing.organization" => "Billing Organization:",
                  "billing.address.street.0" => "Billing Street1:",
                  "billing.address.street.1" => "Billing Street2:",
                  "billing.address.city" => "Billing City:",
                  "billing.address.state" => "Billing State/Province:",
                  "billing.address.pcode" => "Billing Postal Code:",
                  "billing.address.country" => "Billing Country:",
                  "billing.phone" => "Billing Phone:",
                  "billing.fax" => "Billing FAX:",
                  "billing.email" => "Billing Email:"
		            );

		$r["regyinfo"] = array(
                          "referrer" => "http://whois.afilias.info",
                          "registrar" => "Afilias Global Registry Services"
                          );

		$r["regrinfo"] = generic_parser_b($data_str["rawdata"], $items);
		return $r;
		}

	}

?>
