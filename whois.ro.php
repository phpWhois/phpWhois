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

/*
BUG
- date on ro could be given as "mail date" (ex: updated field)
- multiple person for one role, ex: news.ro
- seems the only role listed is registrant
*/

if (!defined('__RO_HANDLER__'))
	define('__RO_HANDLER__', 1);

require_once('whois.parser.php');

class ro_handler
	{

	function parse($data_str, $query)
		{

		$translate = array(
						'fax-no' => 'fax',
						'e-mail' => 'email',
						'nic-hdl' => 'handle',
						'person' => 'name',
						'address' => 'address.',
						'domain-name'	=> '',
						'updated' => 'changed',
						'registration-date' => 'created',
						'domain-status'	=> 'status',
						'nameserver' => 'nserver'
		                  );

		$contacts = array(
						'admin-contact' 	=> 'admin',
						'technical-contact'	=> 'tech',
						'zone-contact' 		=> 'zone',
						'billing-contact' 		=> 'billing'
		                  );

		$extra = array(
						'postal code:' => 'address.pcode'
						);

		$reg = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain','Ymd');

		if (isset($reg['domain']['description']))
			{
			$reg['owner'] = get_contact($reg['domain']['description'],$extra);
			unset($reg['domain']['description']);
			
			foreach($reg as $key => $item)
				{
				if (isset($item['address']))
					{
					$data = $item['address'];
					unset($reg[$key]['address']);
					$reg[$key] = array_merge($reg[$key],get_contact($data,$extra));
					}
				}

			$reg['registered'] = 'yes';
			}
		else
			$reg['registered'] = 'no';

		$r['regrinfo'] = $reg;
		$r['regyinfo'] = array(
                          'referrer' => 'http://www.nic.ro',
                          'registrar' => 'nic.ro'
                          );

		return ($r);
		}
	}
?>
