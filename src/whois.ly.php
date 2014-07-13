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

require_once('whois.parser.php');

if (!defined('__LY_HANDLER__'))
	define('__LY_HANDLER__', 1);

class ly_handler
	{
	function parse($data_str, $query)
		{
		$items = array(
				'owner' => 'Registrant:',
				'admin' => 'Administrative Contact:',
				'tech' => 'Technical Contact:',
				'domain.name' => 'Domain Name:',
				'domain.status' => 'Domain Status:',
				'domain.created' => 'Created:',
				'domain.changed' => 'Updated:',
				'domain.expires' => 'Expired:',
				'domain.nserver' => 'Domain servers in listed order:'
		            );

		$extra = array( 'zip/postal code:' => 'address.pcode' );

		$r['regrinfo'] = get_blocks($data_str['rawdata'], $items);

		if (!empty($r['regrinfo']['domain']['name']))
			{
			$r['regrinfo'] = get_contacts($r['regrinfo'],$extra);
			$r['regrinfo']['domain']['name'] = $r['regrinfo']['domain']['name'][0];
			$r['regrinfo']['registered'] = 'yes';
			}
		else
			{
			$r = '';
			$r['regrinfo']['registered'] = 'no';
			}

		$r['regyinfo'] = array(
                          'referrer' => 'http://www.nic.ly',
                          'registrar' => 'Libya ccTLD'
                          );
		return $r;
		}
	}
?>