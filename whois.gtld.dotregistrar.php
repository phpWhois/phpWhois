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

/* dotregistrar.whois 1.0	David Saez Padros <david@ols.es> */
/* dotregistrar.whois 2.0       David Saez Padros <david@ols.es> */
/* You can check it with zeleste.com */

if (!defined("__DOTREGISTRAR_HANDLER__"))
	define("__DOTREGISTRAR_HANDLER__", 1);

require_once('whois.parser.php');

class dotregistrar_handler
	{

	function parse($data_str, $query)
		{

		$items = array(
                  "owner" => "Registrant:",
                  "admin" => "Administrative Contact",
                  "tech" => "Technical Contact",
                  "zone" => "Zone Contact",
                  "domain.name" => "Domain name:",
                  "domain.nserver." => "Name Server:",
                  "domain.created" => "Record created on",
                  "domain.expires" => "Record expires on",
                  "domain.changed" => "Record last updated on",
                  "domain.status" => "Status:",
                  "domain.name" => "Domain Name:"
		            );

		$r = get_blocks($data_str, $items);
		$r["owner"] = get_contact($r["owner"]);
		$r["admin"] = get_contact($r["admin"]);
		$r["tech"] = get_contact($r["tech"]);
		$r["zone"] = get_contact($r["zone"]);
		format_dates($r, 'dmy');
		return ($r);

		}

	}
?>
