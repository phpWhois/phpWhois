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

/* ripe.whois	1.0 	David Saez 7/6/2002 */

require_once('whois.parser.php');

if (!defined('__AFRINIC_HANDLER__'))
	define('__AFRINIC_HANDLER__', 1);

class afrinic_handler
	{

	function parse($data_str, $query)
		{
		$translate = array(
                      'fax-no' => 'fax',
                      'e-mail' => 'email',
                      'nic-hdl' => 'handle',
                      'person' => 'name',
                      'netname' => 'name',
                      'descr' => 'desc',
                      'organisation' => 'handle',
                      'org-name' => 'organization',
                      'org-type' => 'type'
		                  );

		$contacts = array(
                      'admin-c' => 'admin',
                      'tech-c' => 'tech',
                      'org' => 'owner'
		                  );

		$r = generic_parser_a($data_str, $translate, $contacts, 'network', 'Ymd');

		if (isset($r['owner']['remarks']) && is_array($r['owner']['remarks']))
			while (list($key, $val) = each($r['owner']['remarks']))
				{ 
				$pos = strpos($val,'rwhois://');
			
				if ($pos!==false)
					$r['rwhois'] = strtok(substr($val,$pos),' ');
				}
		
		return $r;
		}

	}
?>
