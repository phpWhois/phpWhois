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

/*
 * Samoan (ws) registration tool
 * Chewy - 2003/Sep/03
 *
 */

if (!defined("__WS_HANDLER__"))
  define("__WS_HANDLER__", 1);

require_once('whois.parser.php');

class ws_handler
  {

  function parse($data_str, $query)
    {
    $items = array(
				"domain.name" => "Domain Name:", "owner.organization" =>
				"Registrant:", "domain.created" => "Domain created on",
				"domain.changed" => "Domain last updated on"
				);

    while (list($key, $val) = each($data_str["rawdata"]))
      {
      $val = trim($val);

      if ($val != "")
        {
        if ($val == "Name servers:")
          {
          $breaker = 0;
          while (list($key, $val) = each($data_str["rawdata"]))
            {
            // There's a blank line before the list- hack it out.
            if (!($value = trim($val)))
              $breaker++;
            if ($breaker == 2)
              break;
            if ($value)
              $r["regrinfo"]["domain"]["nserver"][] = strtok($value, ' ');
            }
          break;
          }

        reset($items);

        while (list($field, $match) = each($items))
        if (strstr($val, $match))
          {
          $v = trim(substr($val, strlen($match)));
          if ($v == "")
            {
            $v = each($data_str["rawdata"]);
            $v = trim($v["value"]);
            }
          $parts = explode(".", $field);
          $var = "\$r[\"regrinfo\"]";
          while (list($fn, $mn) = each($parts))
            $var = $var."[\"".$mn."\"]";
          eval($var."=\"".$v."\";");
          break;
          }
        }
      }
    $r["regyinfo"]["referrer"] = "http://www.samoanic.ws";
    $r["regyinfo"]["registrar"] = "Samoa Nic";

    if (!empty($r["regrinfo"]["domain"]["name"]))
      {
      $r["regrinfo"]["registered"] = "yes";
      if (!empty($r["regrinfo"]["domain"]["nserver"]))
        $r["regrinfo"]["domain"]["status"] = "active";
      else
        {
        if (strstr($r["regrinfo"]["domain"]["sponsor"], "DETAGGED"))
          $r["regrinfo"]["domain"]["status"] = "detagged";
        else
          $r["regrinfo"]["domain"]["status"] = "inactive";
        }
      }
    else
      $r["regrinfo"]["registered"] = "no";

    format_dates($r, 'ymd');
    return ($r);
    }
  }

?>
