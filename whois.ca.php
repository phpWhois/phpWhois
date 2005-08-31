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

/* cira.whois        1.0        by Mark Jeftovic <markjr@easydns.com>  */
/*		     2.0	David Saez <david@ols.es> */
/*				standarized object model */

if (!defined("__CA_HANDLER__"))
	define("__CA_HANDLER__", 1);

require_once('whois.parser.php');

class ca_handler
	{

	function parse($data_str, $query)
		{

		$items = array(
                  "Organization:" => "owner.organization",
                  "Subdomain:" => "domain.name",
                  "Registrar:" => "registrar",
                  "Date-Approved:" => "domain.created",
                  "Date-Modified:" => "domain.changed",
                  "Renewal-Date:" => "domain.expires",
                  "Description:" => "domain.desc",
                  "Admin-Name:" => "admin.name",
                  "Admin-Postal:" => "admin.address.",
                  "Admin-Phone:" => "admin.phone",
                  "Admin-Mailbox:" => "admin.email",
                  "Admin-Fax:" => "admin.fax",
                  "Tech-Name:" => "tech.name",
                  "Tech-Postal:" => "tech.address.",
                  "Tech-Phone:" => "tech.phone",
                  "Tech-Mailbox:" => "tech.email",
                  "Tech-Fax:" => "tech.fax",
                  "NS1-Hostname:" => "domain.nserver.0",
                  "NS2-Hostname:" => "domain.nserver.1",
                  "NS3-Hostname:" => "domain.nserver.2",
                  "NS4-Hostname:" => "domain.nserver.3",
                  "NS5-Hostname:" => "domain.nserver.4",
                  "NS6-Hostname:" => "domain.nserver.5",
                  "Status:" => "domain.status"
		              );

		$r["regrinfo"] = generic_parser_b($data_str["rawdata"], $items, 'ymd');

		$r["regyinfo"]["referrer"] = "http://www.easydns.ca";

		$r["regyinfo"]["registrar"] = $r["regrinfo"]["registrar"];
		unset($r["regrinfo"]["registrar"]);

		return ($r);
		}

	}
?>
