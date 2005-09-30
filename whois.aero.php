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

if (!defined('__AERO_HANDLER__'))
	define('__AERO_HANDLER__', 1);

require_once('whois.parser.php');

class aero_handler
	{

	function parse($data_str, $query)
		{

		$items = array(
                  "Domain ID:" => "domain.handle",
                  "Domain Name:" => "domain.name",
                  "Creation Date:" => "domain.created",
                  'Last Modification Date:' => 'domain.changed',
                  "Expiration Date:" => "domain.expires",
                  "Sponsoring Registrar:" => "domain.sponsor",
                  "Name Server:" => "domain.nserver.",
                  "Registrant ID:" => "owner.handle",
                  "Registrant Name:" => "owner.name",
                  "Registrant Organization:" => "owner.organization",
                  "Registrant Address:" => "owner.address.street",
                  "Registrant City:" => "owner.address.city",
                  'Registrant State/Province:' => 'owner.address.state',
                  "Registrant Postal Code:" => "owner.address.pcode",
                  "Registrant Country:" => "owner.address.country",
                  "Registrant Phone Number:" => "owner.phone",
                  "Registrant Fax Number:" => "owner.fax",
                  "Registrant Email:" => "owner.email",
                  "Admin ID:" => "admin.handle",
                  "Admin Name:" => "admin.name",
                  "Admin Organization:" => "admin.organization",
                  "Admin Address:" => "admin.address.street",
                  "Admin City:" => "admin.address.city",
                  "Admin State/Province:" => "admin.address.state",
                  "Admin Postal Code:" => "admin.address.pcode",                  
                  "Admin Country:" => "admin.address.country",
                  "Admin Phone Number:" => "admin.phone",
                  "Admin Fax Number:" => "admin.fax",
                  "Admin Email:" => "admin.email",
                  "Tech ID:" => "tech.handle",
                  "Tech Name:" => "tech.name",
                  "Tech Organization:" => "tech.organization",
                  "Tech Address:" => "tech.address.street.",
                  "Tech City:" => "tech.address.city",
                  "Tech Postal Code:" => "tech.address.pcode",
                  "Tech State/Province:" => "tech.address.state",
                  "Tech Country:" => "tech.address.country",
                  "Tech Phone Number:" => "tech.phone",
                  "Tech Fax Number:" => "tech.fax",
                  "Tech Email:" => "tech.email",
                  "Billing ID:" => "billing.handle",
                  "Billing Name:" => "billing.name",
                  "Billing Organization:" => "billing.organization",
                  "Billing Address:" => "billing.address.street",
                  "Billing City:" => "billing.address.city",
                  "Billing Postal Code:" => "billing.address.pcode",
                  "Billing State/Province:" => "billing.address.state",
                  "Billing Country:" => "billing.address.country",
                  "Billing Phone Number:" => "billing.phone",
                  "Billing Fax Number:" => "billing.fax",
                  "Billing Email:" => "billing.email"
		            );

		$r['regyinfo'] = array(
                          'referrer' => 'http://www.nic.aero',
                          'registrar' => 'Societe Internationale de Telecommunications Aeronautiques SC'
                          );

		$r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'ymd');
		return $r;
		}

	}

?>
