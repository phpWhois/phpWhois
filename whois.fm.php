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

/* dotfm.whois    1.0    David Saez 4/4/2003 */

if (!defined('__FM_HANDLER__'))
	define('__FM_HANDLER__', 1);

require_once('whois.parser.php');

class fm_handler
	{

	function parse($data, $query)
		{

		$items = array(
				  'owner' => 'Registrant',
                  'admin' => 'Administrative',
                  'tech' => 'Technical',
                  'billing' => 'Billing',
                  'domain.nserver' => 'Name Servers:',
                  'domain.created' => 'Created:',
                  'domain.expires' => 'Expires:',
                  'domain.changed' => 'Modified:',
                  'domain.status' => 'Status:',
                  'domain.sponsor' => 'Registrar:'
                  );

		$r['regrinfo'] = get_blocks($data['rawdata'], $items);
		
		$items = array( 'voice:' => 'phone' );

		if (!empty($r['regrinfo']['domain']['created']))
			{
			$r['regrinfo'] = get_contacts($r['regrinfo'],$items);
			
			if (count($r['regrinfo']['billing']['address']) > 4)
				$r['regrinfo']['billing']['address'] = array_slice($r['regrinfo']['billing']['address'],0,4);

			$r['regrinfo']['registered'] = 'yes';
			format_dates($r['regrinfo']['domain'],'dmY');
			}
		else
			{
			$r = '';
			$r['regrinfo']['registered'] = 'no';
			}

		$r['regyinfo']['referrer'] = 'http://www.dot.dm';
		$r['regyinfo']['registrar'] = 'dotFM';
		$r['rawdata'] = $data['rawdata'];
		
		return ($r);
		}
	}
?>
