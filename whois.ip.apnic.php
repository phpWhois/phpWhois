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

/* apnic.whois	1.0 	David Saez 3/4/2003 */

require_once('whois.parser.php');

if(!defined("__APNIC_HANDLER__"))
  define("__APNIC_HANDLER__",1);

class apnic_handler {

  function parse ($data_str, $query)
  {
  $translate = array (
                      "fax-no" => "fax",
                      "e-mail" => "email",
                      "nic-hdl" => "handle",
                      "person" => "name",
                      "country" => "address",
                      "netname" => "name",
                      "descr" => "desc"
                      );

  $contacts = array (
                      "admin-c" => "admin",
                      "tech-c" => "tech"
                      );

  $r = generic_parser_a($data_str,$translate,$contacts,"network",'Ymd');

  $r["owner"]["organization"] = $r["network"]["desc"][0];
  unset($r["network"]["desc"][0]);
  $r["owner"]["address"]=$r["network"]["desc"];
  unset($r["network"]["desc"]);
  unset($r["network"]["address"]);
  return $r;
  }

}
