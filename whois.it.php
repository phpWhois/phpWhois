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
- nserver -> array
- ContactID in address
*/

if (!defined('__IT_HANDLER__'))
	define('__IT_HANDLER__', 1);

require_once('whois.parser.php');

class it_handler
	{

	function parse($data_str, $query)
		{

		$items = array(
			'domain.name' =>	'Domain:',
			'domain.nserver' =>	'Nameservers',
			'domain.status' =>	'Status:',
			'domain.expires' =>	'Expire Date:',
			'owner' 	=>	'Registrant',
			'admin' 	=>	'Admin Contact',
			'tech' 		=>	'Technical Contacts',
			'registrar' =>	'Registrar'
		            );

		$extra = array(
			'address:' 		=> 'address.',
			'contactid:'	=> 'handle',
			'organization:' => 'organization',
			'created:'		=> 'created',
			'last update:' 	=> 'changed'
		            );

		$r['regrinfo'] = get_blocks($data_str['rawdata'], $items);

		if (isset($r['regrinfo']['owner']))
			$r['regrinfo']['owner'] = get_contact($r['regrinfo']['owner'], $extra);

		if (isset($r['regrinfo']['admin']))
			$r['regrinfo']['admin'] = get_contact($r['regrinfo']['admin'], $extra);
		
		if (isset($r['regrinfo']['tech']))
			$r['regrinfo']['tech'] = get_contact($r['regrinfo']['tech'], $extra);
			
		if (isset($r['regrinfo']['registrar']))
			{
			$r['domain']['registrar'] = $r['regrinfo']['registrar'][0];
			unset($r['regrinfo']['registrar']);
			}

		$r['regyinfo'] = array(
                  'registrar' => 'IT-Nic',
                  'referrer' => 'http://www.nic.it/'
                  );

/*
		if (isset($r['regrinfo']['domain']))
			$r['regrinfo']['domain']['changed'] = substr($r['regrinfo']['domain']['changed'], 0, 10);
		else
			$r['regrinfo']['registered'] = 'no';
*/			
		$r = format_dates($r, 'ymd');
		return ($r);
		}
	}
?>
