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

if (!defined('__UK_HANDLER__'))
	define('__UK_HANDLER__', 1);

require_once('whois.parser.php');

class uk_handler
	{
	function parse($data_str, $query)
		{
		$items = array(
                'owner.organization' => 'Registrant:',
                'owner.address'  => "Registrant's address:",
                'owner.type' 	 => 'Registrant type:',
                'domain.created' => 'Registered on:',
 				'domain.changed' => 'Last updated:',
                'domain.expires' => 'Renewal date:',
                'domain.nserver' => 'Name servers:',
                'domain.sponsor' => 'Registrar:',
                'domain.status'	 => 'Registration status:',
                'domain.dnssec'	 => 'DNSSEC:',
                '' 				 => 'WHOIS lookup made at',
                'disclaimer'	 => '--',
		        );

		$r['regrinfo'] = get_blocks($data_str['rawdata'], $items);

		if (isset($r['regrinfo']['owner']))
			{
			$r['regrinfo']['owner']['organization'] = $r['regrinfo']['owner']['organization'][0];
			$r['regrinfo']['domain']['sponsor'] = $r['regrinfo']['domain']['sponsor'][0];
			$r['regrinfo']['registered'] = 'yes';

			$r = format_dates($r, 'dmy');
			}
		else
			$r['regrinfo']['registered'] = 'no';

		$r['regyinfo'] = array(
                    'referrer' => 'http://www.nominet.org.uk',
                    'registrar' => 'Nominet UK'
		                );
		return $r;
		}
	}
?>