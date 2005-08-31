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

/* nlnic.whois	  1.2    David Saez - updated to use generic3.whois */
/* nlnic.whois    1.1    David Saez - common object model */
/* nlnic.whois    1.0    Matthijs Koot - 2003/01/14 - <koot@cyberwar.nl> */

if (!defined("__NL_HANDLER__"))
	define("__NL_HANDLER__", 1);

require_once('whois.parser.php');

class nl_handler
	{

	function parse($data, $query)
		{

		$items = array(
                  "domain.name" => "Domain name:",
                  "domain.status" => "Status:",
                  "domain.nserver" => "Domain nameservers:",
                  "domain.created" => "Date registered:",
                  "domain.changed" => "Record last updated:",
                  "domain.sponsor" => "Record maintained by:",
                  "owner" => "Registrant:",
                  "admin" => "Administrative contact:",
                  "tech" => "Technical contact:",
                  "zone" => "Registrar:"
		            );

		$r["regyinfo"]["referrer"] = "http://www.domain-registry.nl";
		$r["regyinfo"]["registrar"] = "Stichting Internet Domeinregistratie NL";

		$r["regrinfo"] = get_blocks($data['rawdata'], $items);

		if (!isset($r['regrinfo']['domain']['status']))
			{
			$r["regrinfo"]["registered"] = "no";
			return $r;
			}

		$r["regrinfo"]["tech"] = get_contact($r["regrinfo"]["tech"]);
		$r["regrinfo"]["owner"] = get_contact($r["regrinfo"]["owner"]);
		$r["regrinfo"]["admin"] = get_contact($r["regrinfo"]["admin"]);
		$r["regrinfo"]["zone"] = get_contact($r["regrinfo"]["zone"]);

		$r["regrinfo"]["registered"] = "yes";
		format_dates($r, 'dmy');
		
		return ($r);
		}
	}
?>
