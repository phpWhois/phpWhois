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

/* directnic.whois     1.0     david@ols.es            2003/03/30 */

if (!defined('__PUBLICDOMAINREGISTRY_HANDLER__'))
	define('__PUBLICDOMAINREGISTRY_HANDLER__', 1);

require_once('whois.parser.php');

class publicdomainregistry_handler
	{
	function parse($data_str, $query)
		{
		$items = array(
              'owner' => 'Registrant:',
              'owner#' => '(Registrant):',
              'admin' => 'Administrative Contact',
              'tech' => 'Technical Contact',
              'domain.name' => 'Domain name:',
              'domain.sponsor' => 'Registration Service Provided By:',
              'domain.nserver' => 'Domain servers in listed order:',
              'domain.changed' => 'Record last updated ',
              'domain.created' => 'Record created on',
              'domain.created#' => 'Creation Date:',
              'domain.expires' => 'Record expires on',
              'domain.expires#' => 'Expiration Date:'
		          );

		return easy_parser($data_str, $items, 'mdy', false, true, true);
		}
	}
?>
