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

/* uknic.whois	1.2	David Saez Padros <david@ols.es> */
/*                      Fixed detection of non-existant domains */
/* uknic.whois  1.3     8/1/2002 Added status (active/inactive/detagged) */
/*                      and corrected error for detagged domains */
/*                      (like blue.co.uk) thanx to Adam Greedus */
/* uknic.whois  1.4     16/10/2002 Updated for new Nominet whois output */
/*                      also updated for common object model */
/* uknic.whois  1.5     03/03/2003 minor fixes */

if (!defined("__UK_HANDLER__"))
	define("__UK_HANDLER__", 1);

require_once('whois.parser.php');

class uk_handler
	{

	function parse($data_str, $query)
		{

		$items = array(
                'owner.organization' => 'Registrant:',
                'owner.address' => "Registrant's Address:",
                'domain.created' => 'Registered on:',
 								'domain.changed' => 'Last updated:',
                'domain.expires' => 'Renewal Date:',
                'domain.nserver' => 'Name servers listed in order:',
                'domain.sponsor' => "Registrant's Agent:"
		        );

		$r['regrinfo'] = get_blocks($data_str['rawdata'], $items);

		$r['regrinfo']['owner']['organization'] = $r['regrinfo']['owner']['organization'][0];
		$r['regrinfo']['domain']['sponsor'] = $r['regrinfo']['domain']['sponsor'][0];

		unset($r['regrinfo']['domain']['nserver'][count
				($r['regrinfo']['domain']['nserver']) - 1]);

		$r = format_dates($r, 'dmy');
		return $r;
		}

	}

?>
