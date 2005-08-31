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

/* isnic.whois  1.00    David Saez <david@ols.es> */

if (!defined("__IS_HANDLER__"))
	define("__IS_HANDLER__", 1);

require_once('whois.parser.php');

class is_handler
	{

	function parse($data_str, $query)
		{

		$translate = array(
                      "fax-no" => "fax",
                      "e-mail" => "email",
                      "nic-hdl" => "handle",
                      "person" => "name"
		                  );

		$contacts = array(
                      "owner-c" => "owner",
                      "admin-c" => "admin",
                      "tech-c" => "tech",
                      "billing-c" => "billing",
                      "zone-c" => "zone"
		                  );

		$r["regyinfo"] = array(
                          "referrer" => "http://www.isnic.is",
                          "registrar" => "ISNIC"
                          );

		$reg = generic_parser_a($data_str["rawdata"], $translate, $contacts, 'domain', 'mdy');

		if (isset($reg['domain']['descr']))
			{
			$reg['owner']['name'] = array_shift($reg['domain']['descr']);
			$reg['owner']['address'] = $reg['domain']['descr'];
			unset($reg['domain']['descr']);
			}

		$r["regrinfo"] = $reg;
		return ($r);
		}
	}
?>
