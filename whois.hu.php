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

/* hunic.whois	0.01	Manuel Machajdik <machajdik@gmxpro.net> */
/* based upon org.whois and atnic.whois */

if(!defined("__HU_HANDLER__"))
  define("__HU_HANDLER__",1);

require_once('whois.parser.php');

class hu_handler {

  function parse ($data_str, $query) {


    $translate = array (
                        "fax-no" => "fax",
                        "e-mail" => "email",
                        "hun-id" => "handle",
                        "person" => "name",
                        "domain_pri_ns" => "nserver",
                        "domain_sec_ns" => "nserver",
                        "person" => "name",
                        "org" => "organization",
                        "registered" => "created"
                        );

    $contacts = array (
                        "registrar" => "owner",
                        "admin-c" => "admin",
                        "tech-c" => "tech",
                        "billing-c" => "billing",
                        "zone-c" => "zone"		
                      );

    $r["rawdata"]=$data_str["rawdata"];

    $r["regyinfo"]=array("referrer"=>"http://www.nic.hu","registrar"=>"HUNIC");

    // make those broken hungary comments standards-conforming

    for ($i=1; $i<count($data_str['rawdata']); $i++) {

      if (substr($data_str['rawdata'][$i+1],0,7) != "domain:") {
        $data_str['rawdata'][$i] = "% ".$data_str['rawdata'][$i];
	     }
      else {
		  break;
	   }
    }

  // replace first found hun-id with owner-hun-id (will be parsed later on)

  for ($i=1; $i<count($data_str['rawdata']); $i++) {

	 if (substr($data_str['rawdata'][$i],0,7) == "hun-id:") {
		  $data_str['rawdata'][$i] = "owner-".$data_str['rawdata'][$i];
		  break;
	   }

  }

  $reg = generic_parser_a($data_str["rawdata"],$translate,$contacts);

  if ($reg['domain']) {

  while (list($key,$val)=each($reg['domain']))
      {
        if (is_array($val)) continue;
        $v=trim(substr(strstr($val,":"),1));
        if ($key == "organization")
           { $reg["owner"]["organization"]=$val;
             unset($reg['domain'][$key]);
             continue;
           }
        if ($key == "owner-hun-id")
           { $reg["owner"]["handle"]=$val;
             unset($reg['domain'][$key]);
             continue;
           }
        if ($key == "address")
           { $reg["owner"]["address"]=$val;
             unset($reg['domain'][$key]);
             continue;
           }
        if ($key == "phone")
           { $reg["owner"]["phone"]=$val;
             unset($reg['domain'][$key]);
             continue;
           }
        if ($key == "fax")
           { $reg["owner"]["fax"]=$val;
             unset($reg['domain'][$key]);
             continue;
           }
      }
  }

  $r["regrinfo"]=$reg;
  format_dates($r,'ymd');
  return($r);
  }
}
