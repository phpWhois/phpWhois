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

/* lacnic.whois	1.0 	David Saez 3/7/2003 */

require_once('whois.parser.php');

if (!defined("__LACNIC_HANDLER__"))
	define("__LACNIC_HANDLER__", 1);

class lacnic_handler
	{

	function parse($data_str, $query)
		{
		$translate = array(
                      "fax-no" => "fax",
                      "e-mail" => "email",
                      "nic-hdl" => "handle",
                      "person" => "name",
                      "netname" => "name",
                      "descr" => "desc",
                      "country" => "address.country"
		                  );

		$contacts = array(
                      "admin-c" => "admin",
                      "tech-c" => "tech",
                      "owner-c" => "owner"
		                  );

		$r = generic_parser_a($data_str, $translate, $contacts, "network");

		if (isset($r['network']['nsstat']))
			{
			unset($r['network']['nsstat']);
			unset($r['network']['nslastaa']);
			}

		if (isset($r['network']['owner']))
			{
			$r['owner']['organization'] = $r['network']['owner'];
			unset($r['network']['owner']);
			unset($r['network']['responsible']);
			unset($r['network']['address']);
			unset($r['network']['phone']);
			unset($r['network']['inetrev']);
			unset($r['network']['ownerid']);
			}

		return $r;
		}

	}
?>
