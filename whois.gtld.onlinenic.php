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

if (!defined('__ONLINENIC_HANDLER__'))
	define('__ONLINENIC_HANDLER__', 1);

require_once('whois.parser.php');

class onlinenic_handler
	{

	function parse($data_str, $query)
		{

		$items = array(
                  'owner' => 'Registrant:',
                  'admin' => 'Administrator:',
                  'tech' => 'Technical Contactor:',
                  'billing' => 'Billing Contactor:',
                  'domain.name' => 'Domain name:',
                  'domain.name#' => 'Domain Name:',
                  'domain.nserver' => 'Domain servers in listed order:',
                  'domain.created' => 'Record created on ',
                  'domain.expires' => 'Record expired on ',
                  'domain.changed' => 'Record last updated at '
		              );

		$extra = array(
					'tel--' => 'phone',
					'tel:' => 'phone',
					'tel --:' => 'phone',
					'email-:' => 'email',
					'email:' => 'email',
					'mail:' => 'email',					
					'name--' => 'name',
					'org:' => 'organization',
					'zipcode:' => 'address.pcode',
					'postcode:' => 'address.pcode',
					'address:' => 'address.street',
					'city:' => 'address.city',
					'province:' => '',
					',province:' => '',
					',country:' => 'address.country'
					);
					
		$r = easy_parser($data_str, $items, 'mdy',$extra,false,true);

		foreach($r as $key => $part)
			if (isset($part['email']))
				{
				@list($email,$phone) = explode(' ',$part['email']);
				$email = str_replace('(','',$email);
				$email = str_replace(')','',$email);
				$r[$key]['email'] = $email;
				if ($phone != '') $r[$key]['phone'] = $phone;
				}
				
		return ($r);
		}

	}

?>
