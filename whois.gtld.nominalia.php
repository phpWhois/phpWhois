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

if (!defined('__NOMINALIA_HANDLER__'))
	define('__NOMINALIA_HANDLER__', 1);

require_once('whois.parser.php');

class nominalia_handler
	{

	function parse($data_str, $query)
		{

		$items = array(
                'Domain name:' 						=> 'domain.name',
                'Primary Name Server Hostname:'		=> 'domain.nserver.0',
                'Secondary Name Server Hostname:'	=>'domain.nserver.1',
                'Created on:' 						=> 'domain.created',
                'Expires on:'						=> 'domain.expires',
                'Updated on:'						=> 'domain.changed',
                'Registrant Name:' 					=> 'owner.name',
                'Registrant Address:'				=> 'owner.address.street',
                'Registrant City:'					=> 'owner.address.city',
                'Registrant Postal Code:'			=> 'owner.address.pcode',
                'Registrant Country:'				=> 'owner.address.country',
                'Administrative Contact Name:'				=> 'admin.name',
                'Administrative Contact Organization:'		=> 'admin.organization',
                'Administrative Contact Address:'			=> 'admin.address.street',
                'Administrative Contact City:'				=> 'admin.address.city',
                'Administrative Contact Postal Code:'		=> 'admin.address.pcode',
                'Administrative Contact Country:'			=> 'admin.address.country',
                'Administrative Contact Email:'				=> 'admin.email',
                'Administrative Contact Tel:'				=> 'admin.phone',
                'Administrative Contact Fax:'				=> 'admin.fax',
                'Technical Contact Contact Name:'			=> 'tech.name',
                'Technical Contact Contact Organization:'	=> 'tech.organization',
                'Technical Contact Contact Address:'		=> 'tech.address.street',
                'Technical Contact Contact City:'			=> 'tech.address.city',
                'Technical Contact Contact Postal Code:'	=> 'tech.address.pcode',
                'Technical Contact Contact Country:'		=> 'tech.address.country',
                'Technical Contact Contact Email:'			=> 'tech.email',
                'Technical Contact Contact Tel:'			=> 'tech.phone',
                'Technical Contact Contact Fax:'			=> 'tech.fax'
		            );
		
		return generic_parser_b($data_str, $items, 'ymd');
		}
	}
?>
