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

/* bulkregistercom.whois	1.0	mark jeftovic	1999/12/06 */
/* bulkregistercom.whois	1.1	Matthijs Koot	2003/01/14 */
/* bulkregistercom.whois	1.2	David Saez	    2005/08/31 */

if (!defined('__BULKR_HANDLER__'))
	define('__BULKR_HANDLER__', 1);


/*#################################################
Matthijs Koot - 2003/01/14 - matthijs[AT]koot[DOT]biz - http://www.koot.biz
--> BulkRegister V1.1 UPDATE NOTE:

BulkRegister.com has several antispam measures, which include
grammatic algorithms for changing their Whois output every
x request or every x seconds or so. This update includes
new regexp's to extract the information.

In addition, whois.bulkregister.com will only allow few
whois request to be made from the same IP-address within
a specific period of time. Exceeding requests will be
bounced!

I have tested it on dozens of domains, but as I'm a
regexp newbie it *might* be buggy - bugreports are welcome!

#################################################*/

require_once('whois.parser.php');

class bulkr_handler
	{

	function parse($data_str, $query)
		{
		$items = array(
                'admin' 			=> 'Administrative Contact',
                'tech' 				=> 'Technical Contact',
                'billing' 			=> 'Billing Contact',
                'domain.name' 		=> 'Domain name:',
                'domain.nserver.' 	=> 'Domain servers in listed order:',
                'dummy' 			=> 'Record update'
		            );

		$r = get_blocks($data_str, $items);
		
		if (isset($r['admin']))
			$r['admin'] = get_contact($r['admin']);
		
		if (isset($r['admin']))	
			$r['tech'] = get_contact($r['tech']);
		
		if (isset($r['billing']))	
			$r['billing'] = get_contact($r['billing']);

		unset($r['dummy']);
		reset($data_str); 
		
		while (list($key, $val) = each($data_str))
			if (trim($val)=='') break;

		while (list($key, $val) = each($data_str))
			if (trim($val)!='') break;
			
		$r['owner']['name'] = $val;

		while (list($key, $val) = each($data_str))
			{			
			if (trim($val)=='') break;
			$r['owner']['address'][] = $val;
			}
			
		format_dates($r, 'ymd');
		return ($r);
		}
	}
?>
