<?php

/*
  Whois2.php        PHP classes to conduct whois queries
  
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

/* denic.whois        1.0 by david saez */
/* denic.whois        0.4 by Oliver Notka <notka@ettel-gmbh.de> */
/* Fixed error when domain doesnt exist */

/* denic.whois        0.3 by David Saez <david@ols.es> */    
/* denic.whois        0.2 by Elmar K. Bins <elmi@4ever.de> */
/* based upon brnic.whois by Marcelo Sanches <msanches@sitebox.com.br> */
/* and        atnic.whois by Martin Pircher <martin@pircher.net> */

/* this version does not yet deliver contact data, but handles only */

if(!defined("__DE_HANDLER__")) define("__DE_HANDLER__",1);

require_once('generic3.whois');
require_once('getdate.whois');

class de_handler {

	function parse ($data_str) {

		$items=array(
			'domain.name' => 'domain:',
			'domain.nserver' => 'nserver',
			'domain.status' => 'status:',
			'domain.changed' => 'changed:',
			'owner' => '[holder]',
			'admin' => '[admin-c]',
			'tech' => '[tech-c]',
			'zone' => '[zone-c]'
			);

		$extra=array(
			'address:' => 'address.street',
			'city:' => 'address.city',
			'pcode:' => 'address.zcode',
			'country:' => 'address.country',
			'name:' => 'name',
			'remarks:' => ''
			);

		$r['regrinfo'] = get_blocks($data_str['rawdata'],$items);

		$r['regrinfo']['owner']=get_contact($r['regrinfo']['owner'],$extra);
		$r['regrinfo']['admin']=get_contact($r['regrinfo']['admin'],$extra);
		$r['regrinfo']['tech']=get_contact($r['regrinfo']['tech'],$extra);
		$r['regrinfo']['zone']=get_contact($r['regrinfo']['zone'],$extra);

		$r["rawdata"] = $data_str["rawdata"];

		$r["regyinfo"] = array( "registrar" => "DENIC eG",
					"referrer" => "http://www.denic.de/");

		$r['regrinfo']['domain']['changed']=substr($r['regrinfo']['domain']['changed'],0,10);
		$r=format_dates($r,'ymd');
		return($r);
	}
}
?>
