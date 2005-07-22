<?php

/*
  Whois2.php	PHP classes to conduct whois queries
  
  Copyright (C)1999,2000 easyDNS Technologies Inc. & Mark Jeftovic 
  
  Maintained by Mark Jeftovic <markjr@easydns.com>
  
  For the most recent version of this package: 
  
  http://www.easydns.com/~markjr/whois2/
  
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

/* gtld.whois	1.0	mark jeftovic	1999/12/06 */
/* gtld.whois   1.1     david@ols.es    2003/02/09 */
/* gtld.whois   1.2     david@ols.es    2003/09/12 */

if(!defined("__GTLD_HANDLER__")) define("__GTLD_HANDLER__",1);

require_once("generic2.whois");

class gtld_handler extends Whois {

	var $HANDLER_VERSION = "1.1";

	var $REG_FIELDS = array(
		"regrinfo.domain.name" => "Domain Name:",
		"regyinfo.registrar" => "Registrar:",
		"regyinfo.whois" => "Whois Server:",
		"regyinfo.referrer" => "Referral URL:",
		"regrinfo.domain.nserver." => "Name Server:", // identical descriptors
		"regrinfo.domain.changed" => "Updated Date:",
		"regrinfo.domain.changed" => "Last Updated On:",
		"regrinfo.domain.status" => "Status:", 
		"regrinfo.domain.created" => "Creation Date:",
		"regrinfo.domain.created" => "Created On:",
		"regrinfo.domain.expires" => "Expiration Date:",
		"regrinfo.domain.changed" => "Updated Date:"
		);             

	var $REGISTRARS = array(
		"ALABANZA, INC." => "bulkr",
		'ARSYS INTERNET, S.L. D/B/A NICLINE.COM' => "nicline",
		"ASCIO TECHNOLOGIES, INC." => "ascio",
		"BULKREGISTER.COM, INC."  => "bulkr",
		"BULKREGISTER, LLC." => "bulkr",
		'COMPUTER SERVICES LANGENBACH GMBH DBA JOKER.COM' => 'joker',
		"CORE INTERNET COUNCIL OF REGISTRARS" => "core",
		'CRONON AG BERLIN, NIEDERLASSUNG REGENSBURG' => 'cronon',
		"DOMAIN BANK, INC." => "domainbank",
		"DOMAINDISCOVER" => "buydomains",
		"DOTSTER, INC." => "dotster",
		"ENOM, INC." => 'enom',
		"GO DADDY SOFTWARE, INC." => 'godaddy',
		"IHOLDINGS.COM, INC. D/B/A DOTREGISTRAR.COM" => 'dotregistrar',
		"INNERWISE, INC. D/B/A ITSYOURDOMAIN.COM" => 'innerwise',
		"INTERCOSMOS MEDIA GROUP, INC. D/B/A DIRECTNIC.COM" => "directnic",
		"INTERDOMAIN, S.A." => "interdomain",
		"MELBOURNE IT, LTD. D/B/A INTERNET NAMES WORLDWIDE" => "inwwcom",
		'MONIKER ONLINE SERVICES, INC.' => 'moniker',
		'NETWORK SOLUTIONS, INC.' => 'netsol',
		'NETWORK SOLUTIONS, LLC.' => 'netsol',
		'REGISTER.COM, INC.' => 'registercom',
		'SCHLUND+PARTNER AG' => 'schlund',
		'STARGATE HOLDINGS CORP.' => 'stargate',
		'TLDS, INC. DBA SRSPLUS' => 'srsplus',
		'TLDS, LLC DBA SRSPLUS' => 'srsplus',
		'TUCOWS, INC.' => 'opensrsnet',
		'TUCOWS INC.' => 'opensrsnet',
		'TV CORPORATION' => 'tvcorp',
		'WILD WEST DOMAINS, INC.' => 'godaddy'
		);

	function parse ($data,$query)
		{
		$this->Query = $query;
		$this->SUBVERSION = sprintf("%s-%s", $query["handler"], $this->HANDLER_VERSION);
		$this->result = generic_whois($data["rawdata"],$this->REG_FIELDS,'dmy');

		unset($this->Query["handler"]);
		
		/*
		if($this->HACKS["nsi_referral_loop"] &&
		   ($this->result["regyinfo"]["whois"] == $this->HACKS["wrong_netsol_whois"])) {
			$this->Query["server"] = $this->HACKS["real_netsol_whois"];
		} else {
		*/
			if (isset($this->result["regyinfo"]["whois"]))
				$this->Query["server"] = $this->result["regyinfo"]["whois"];
		//}

		$this->result["rawdata"] = $this->GetData($this->Query["string"]);
		// david@ols.es 16/10/2002 Fixes rawdata
		
		if (!isset($this->result["rawdata"]["rawdata"])) {
			$this->Query["errstr"]="Domain not found";
			unset($this->result["rawdata"]);
			return;
		}

		if (isset($this->result["rawdata"]["rawdata"]))
			$this->result["rawdata"] = $this->result["rawdata"]["rawdata"];

		@$this->Query["handler"] = $this->REGISTRARS[$this->result["regyinfo"]["registrar"]];

		$this->result["regrinfo"]["registered"] = $this->result["registered"];
		unset($this->result["registered"]);

		if (!empty($this->Query["handler"])) {
			$this->Query["file"] = sprintf("whois.gtld.%s.php", $this->Query["handler"]);
			$regrinfo = $this->Process($this->result["rawdata"]);
			$this->result["regrinfo"] = merge_results($this->result["regrinfo"],$regrinfo);
		}
		return $this->result;
	}
}

function merge_results ( $a1, $a2 )
{

reset($a2);

while (list($key, $val) = each($a2)) {
	if (isset($a1[$key])) {
		if (is_array($val))
			$a1[$key]=merge_results($a1[$key],$val);
		else {
			$val=trim($val);
			if ($val!='')
				$a1[$key]=$val;
			}
		}
	else
		$a1[$key]=$val;
	}

return $a1;
}

?>
