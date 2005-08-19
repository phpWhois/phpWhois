<?php 
/*
Whois.php        PHP classes to conduct whois queries

Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic

Maintained by David Saez (david@ols.es)

For the most recent version of this package visit:

http://phpwhois.sourceforge.net

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

/* inwwcom.whois	1.0	jeremiah bellomy	2000/04/06 */
/* inwwcom.whois        2.0     david@ols.es            2003/02/09 */

require_once('whois.parser.php');
    
if(!defined("__INWWCOM_HANDLER__")) define("__INWWCOM_HANDLER__",1);

class inwwcom_handler {

	function parse($data_str,$query) {
		$items = array ( "domain.name" => "Domain Name..........",
                                 "domain.created" => "Registration Date....",
				 "domain.expires" => "Expiry Date..........",
				 "owner.name" => "Organisation Name....",
				 "owner.address." => "Organisation Address.",
				 "admin.name" => "Admin Name...........",
				 "admin.address." => "Admin Address........",
				 "admin.email" => "Admin Email..........",
				 "admin.phone" => "Admin Phone..........",
				 "admin.fax" => "Admin Fax............",
				 "tech.name" => "Tech Name............",
				 "tech.address." => "Tech Address.........",
				 "tech.email" => "Tech Email...........",
				 "tech.phone" => "Tech Phone...........",
				 "tech.fax" => "Tech Fax.............",
				 "domain.nserver." => "Name Server.........."
                               );
 
		return generic_parser_b($data_str,$items);
	}

}

?>
