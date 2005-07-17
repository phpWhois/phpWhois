<?php
/*
Whois2.php        PHP classes to conduct whois queries

Copyright (C)1999,2000 easyDNS Technologies Inc. & Mark Jeftovic

Maintained by Mark Jeftovic <markjr@easydns.com>

For the most recent version of this package:

http://www.easydns.com/~markjr/whois2/

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

/* neulevel.whois        2.2        David Saez */
/* neulevel.whois        1.0        by Brian Blood <brian@macserve.net>  */

if(!defined("__BIZ_HANDLER__")) define("__BIZ_HANDLER__",1);

require_once('generic2.whois');

class biz_handler extends Whois {

function parse ($data_str) {

	$items = array( "domain.name" => "Domain Name:",
			"domain.handle" => "Domain ID:",
			"domain.sponsor" => "Sponsoring Registrar:",
			"domain.status" => "Domain Status:",
			"domain.nserver." => "Name Server:",
			"domain.created" => "Domain Registration Date:",
			"domain.expires" => "Domain Expiration Date:",
			"domain.changed" => "Domain Last Updated Date:",
			"owner.handle" => "Registrant ID:",
			"owner.name" => "Registrant Name:",
			"owner.organization" => "Registrant Organization:",
			"owner.address.street.0" => "Registrant Address1:",
			"owner.address.street.1" => "Registrant Address2:",
			"owner.address.zcode" => "Registrant Postal Code:",
			"owner.address.city" => "Registrant City:",
			"owner.address.state" => "Registrant State/Province:",
			"owner.address.country" => "Registrant Country:",
			"owner.phone" => "Registrant Phone Number:",
			'owner.fax' => 'Registrant Facsimile Number:',
			"owner.email" => "Registrant Email:",
			"admin.handle" => "Administrative Contact ID:",
                        "admin.name" => "Administrative Contact Name:",
                        "admin.organization" => "Administrative Contact Organization:",
                        "admin.address.street.0" => "Administrative Contact Address1:",
			"admin.address.street.1" => "Administrative Contact Address2:",
                        "admin.address.zcode" => "Administrative Contact Postal Code:",
                        "admin.address.city" => "Administrative Contact City:",
                        "admin.address.state" => "Administrative Contact State/Province:",
                        "admin.address.country" => "Administrative Contact Country:",
                        "admin.phone" => "Administrative Contact Phone Number:",
                        "admin.email" => "Administrative Contact Email:",
			'admin.fax' => 'Administrative Contact Facsimile Number:',
			"tech.handle" => "Technical Contact ID:",
                        "tech.name" => "Technical Contact Name:",
                        "tech.organization" => "Technical Contact Organization:",
                        "tech.address.street.0" => "Technical Contact Address1:",
			"tech.address.street.1" => "Technical Contact Address2:",
                        "tech.address.zcode" => "Technical Contact Postal Code:",
                        "tech.address.city" => "Technical Contact City:",
                        "tech.address.state" => "Technical Contact State/Province:",
                        "tech.address.country" => "Technical Contact Country:",
                        "tech.phone" => "Technical Contact Phone Number:",
			'tech.fax' => 'Technical Contact Facsimile Number:',
                        "tech.email" => "Technical Contact Email:",
			"billing.handle" => "Billing Contact ID:",
                        "billing.name" => "Billing Contact Name:",
                        "billing.organization" => "Billing Contact Organization:",
                        "billing.address.street.1" => "Billing Contact Address1:",
			"billing.address.street.0" => "Billing Contact Address2:",
                        "billing.address.zcode" => "Billing Contact Postal Code:",
                        "billing.address.city" => "Billing Contact City:",
                        "billing.address.state" => "Billing Contact State/Province:",
                        "billing.address.country" => "Billing Contact Country:",
                        "billing.phone" => "Billing Contact Phone Number:",
			'billing.fax' => 'Billing Contact Facsimile Number:',
                        "billing.email" => "Billing Contact Email:"
		);

	$r['rawdata'] = $data_str['rawdata'];
	$r['regrinfo'] = generic_whois($data_str['rawdata'],$items,'-md--y');

	$r['regyinfo'] = array( 'referrer'  => 'http://www.neulevel.biz', 
				'registrar' => 'NEULEVEL' );
	return($r);
}

}
?>
