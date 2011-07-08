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

if (!defined('__EDU_HANDLER__'))
	define('__EDU_HANDLER__', 1);

require_once('whois.parser.php');

class edu_handler
	{
	function parse($data_str, $query)
		{
		$items = array(
				'domain.name' => 'Domain name:',
                'domain.sponsor' => 'Registrar:',
                'domain.nserver' => 'Name Servers:',
                'domain.changed' => 'Domain record last updated:',
                'domain.created' => 'Domain record activated:',
                'owner'	=> 'Registrant:',
                'admin' => 'Administrative Contact:',
                'tech' => 'Technical Contact:',
                'billing' => 'Billing Contact:'
		            );

		$r['regrinfo'] = easy_parser($data_str['rawdata'], $items, 'dmy');

		if (isset($b['tech']))
			{
			if ($r['regrinfo']['tech']['name'] == 'Same as above')
				$r['regrinfo']['tech'] = $r['regrinfo']['admin'];
			}

		$r['regyinfo']['referrer'] = 'http://whois.educause.net';
		$r['regyinfo']['registrar'] = 'EDUCASE';
		return ($r);
		}
	}
?>