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

/* benic.whois        1.0        Matthijs Koot <koot@cyberwar.nl> */
/* benic.whois        1.2	 David Saez */

require_once('whois.parser.php');

if (!defined("__BE_HANDLER__"))
	define("__BE_HANDLER__", 1);

class be_handler
	{

	function parse($data, $query)
		{

		$items = array(
                "domain.name" => "Domain:",
                "domain.status" => "Status:",
				"domain.nserver" => "Nameservers:",
                "domain.created" => "Registered:",
                "owner" => "Licensee:",
                "admin" => "Onsite Contacts:",
                "tech" => "Agent Technical Contacts:",
				'agent' => 'Agent:'
				);

		$r["rawdata"] = $data["rawdata"];
		$r["regyinfo"]["referrer"] = "http://www.domain-registry.nl";
		$r["regyinfo"]["registrar"] = "DNS Belgium";

		$r["regrinfo"] = get_blocks($data["rawdata"], $items);

		if (isset($r['regrinfo']['domain']['name']))
			{
			$r['regrinfo']['registered'] = 'yes';
			$r['regrinfo']['tech'] = get_contact($r['regrinfo']['tech']);
			$r['regrinfo']['owner'] = get_contact($r['regrinfo']['owner']);

			if (isset($r['regrinfo']['admin']))
				$r['regrinfo']['admin'] = get_contact($r['regrinfo']['admin']);

			if (isset($r['regrinfo']['agent']))
				{
				$sponsor = get_contact($r['regrinfo']['agent']);
				unset($r['regrinfo']['agent']);
				$r['regrinfo']['domain']['sponsor'] = $sponsor['name'];
				}

			$r = format_dates($r, '-mdy');
			}
		else
			$r['regrinfo']['registered'] = 'no';

		return ($r);
		}
	}
?>
