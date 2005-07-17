<?php
/*
Whois2.php    PHP classes to conduct whois queries

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

/* dotfm.whois    1.0    David Saez 4/4/2003 */

if(!defined("__FM_HANDLER__")) define("__FM_HANDLER__",1);

require_once('generic2.whois');
require_once('generic3.whois');

class fm_handler extends Whois {

function parse ($data) 
{

    $items = array( "owner" => "Registrant",
                    "admin" => "Administrative",
                    "tech" => "Technical",
                    "billing" => "Billing" );

    $blocks = get_blocks($data['rawdata'],$items);

    $items = array(
			"name" => "FM Domain:",
			"nserver.0" => "Primary Hostname:",
			"nserver.1" => "Secondary Hostname:",
			"expires" => "Renewal Date:"
		  );

    $r['regrinfo']['domain'] = generic_whois($data['rawdata'],$items);

    $items = array (
			'organization' => 'Organiztion:',
			'name' => 'Name:',
			'address.0' => 'Address:',
			'address.1' => 'City, State Zip:',
			'address.country' => 'Country:',
			'phone' => 'Phone:',
			'fax' => 'Fax:',
			'email' => 'Email:'
			);

    $r['rawdata']=$data['rawdata'];
  
    while (list($key, $val) = each($blocks))
	{
	$r['regrinfo'][$key] = generic_whois($val,$items);
	}

    $r['regyinfo']['referrer']='http://www.dot.dm';
    $r['regyinfo']['registrar']='dotFM';

    return($r);
}
}
?>
