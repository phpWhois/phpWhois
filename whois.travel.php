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

if (!defined('__TRAVEL_HANDLER__'))
	define('__TRAVEL_HANDLER__', 1);

require_once('whois.parser.php');

class travel_handler
	{

	function parse($data_str, $query)
		{
		$items = array(
                    'Domain Name:' 							=> 'domain.name',
					'Domain ID:'							=> 'domain.handle',
                    'Domain Status:'						=> 'domain.status',
                    'Sponsoring Registrar:' 				=> 'domain.sponsor',
                    'Name Server:' 							=> 'domain.nserver.',
                    'Domain Registration Date:'				=> 'domain.created',
                    'Domain Last Updated Date:'				=> 'domain.changed',
                    'Domain Expiration Date:'				=> 'domain.expires',
                    'Registrant Name:' 						=> 'owner.name',
                    'Registrant ID:' 						=> 'owner.handle',
                    'Registrant Address1:' 					=> 'owner.address.address.0',
                    'Registrant Address2:' 					=> 'owner.address.address.1',
                    'Registrant Postal Code:' 				=> 'owner.address.pcode',
                    'Registrant City:' 						=> 'owner.address.city',
                    'Registrant State/Province:' 			=> 'owner.address.state',
                    'Registrant Country:' 					=> 'owner.address.country',
                    'Registrant Phone Number:' 				=> 'owner.phone',
                    'Registrant Email:' 					=> 'owner.email',
                    'Administrative Contact Name:' 			=> 'admin.name',
                    'Administrative Contact ID:' 			=> 'admin.handle',
                    'Administrative Contact Address1:' 		=> 'admin.address.address.0',
                    'Administrative Contact Address2:' 		=> 'admin.address.address.1',
                    'Administrative Contact Postal Code:'	=> 'admin.address.pcode',
                    'Administrative Contact City:' 			=> 'admin.address.city',
                    'Administrative Contact State/Province:'=> 'admin.address.state',
                    'Administrative Contact Country:' 		=> 'admin.address.country',
                    'Administrative Contact Phone Number:' 	=> 'admin.phone',
                    'Administrative Contact Email:' 		=> 'admin.email',
                    'Technical Contact Name:' 				=> 'tech.name',
                    'Technical Contact ID:' 				=> 'tech.handle',
                    'Technical Contact Address1:' 			=> 'tech.address.address.0',
                    'Technical Contact Address2:' 			=> 'tech.address.address.1',
                    'Technical Contact Postal Code:'		=> 'tech.address.pcode',
                    'Technical Contact City:' 				=> 'tech.address.city',
                    'Technical Contact State/Province:' 	=> 'tech.address.state',
                    'Technical Contact Country:' 			=> 'tech.address.country',
                    'Technical Contact Phone Number:' 		=> 'tech.phone',
                    'Technical Contact Email:' 				=> 'tech.email',
                    'Billing Contact Name:' 				=> 'bill.name',
                    'Billing Contact ID:' 					=> 'bill.handle',
                    'Billing Contact Address1:' 			=> 'bill.address.address.0',
                    'Billing Contact Address2:' 			=> 'bill.address.address.1',
                    'Billing Contact Postal Code:'			=> 'bill.address.pcode',
                    'Billing Contact City:' 				=> 'bill.address.city',
                    'Billing Contact State/Province:' 		=> 'bill.address.state',
                    'Billing Contact Country:' 				=> 'bill.address.country',
                    'Billing Contact Phone Number:' 		=> 'bill.phone',
                    'Billing Contact Email:' 				=> 'bill.email'
		              );

		$r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items);
		$r['regyinfo']['referrer'] = 'http://www.nic.travel/';
		$r['regyinfo']['registrar'] = 'Tralliance Corporation';
		return ($r);
		}
	}

?>
