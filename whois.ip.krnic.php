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

/* krnic.whois	1.0 	David Saez 7/6/2002 */

if (!defined("__KRNIC_HANDLER__"))
	define("__KRNIC_HANDLER__", 1);

require_once('whois.parser.php');

class krnic_handler
	{

	function parse($data_str, $query)
		{

		$blocks = array(
                    "owner" => "[ Organization Information ]",
                    "admin" => "[ Admin Contact Information]",
                    "tech" => "[ Technical Contact Information ]",
                    "abuse" => "[ ISP Network Abuse Contact Information ]",
                    "network.inetnum" => "IPv4 Address       :",
                    "network.name" => "Network Name       :",
                    "network.mnt-by" => "Connect ISP Name   :",
                    "network.created" => "Registration Date  :"
		              );

		$items = array(
                    "Orgnization ID     :" => "handle",
                    "Org Name           :" => "organization",
                    "Name               :" => "name",
                    "Address            :" => "address.street",
                    "Zip Code           :" => "address.pcode",
                    "State              :" => "address.state",
                    "Phone              :" => "phone",
                    "Fax                :" => "fax",
                    "E-Mail             :" => "email"
		              );

		$r = get_blocks($data_str, $blocks);

		$r["owner"] = generic_parser_b($r["owner"], $items, 'Ymd', false);
		$r["admin"] = generic_parser_b($r["admin"], $items, 'Ymd', false);
		$r["tech"] = generic_parser_b($r["tech"], $items, 'Ymd', false);
		$r["abuse"] = generic_parser_b($r["abuse"], $items, 'Ymd', false);

		$r = format_dates($r, 'Ymd');
		return ($r);
		}

	}
?>
