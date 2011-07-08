<?php
/*
Whois.php        PHP classes to conduct whois queries

Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic

Maintained by David Saez

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

if (!defined('__IE_HANDLER__'))
	define('__IE_HANDLER__', 1);

require_once('whois.parser.php');

class ie_handler
	{
	function parse($data_str, $query)
		{
		$translate = array(
			'nic-hdl' => 'handle',
			'person' => 'name',
			'renewal' => 'expires'
			);

		$contacts = array(
                    'admin-c' => 'admin',
                    'tech-c' => 'tech',
		                );

		$reg = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');

		if (isset($reg['domain']['descr']))
			{
			$reg['owner']['organization'] = $reg['domain']['descr'][0];
			unset($reg['domain']['descr']);
			}

		$r['regrinfo'] = $reg;
		$r['regyinfo'] = array(
                    'referrer' => 'http://www.domainregistry.ie',
                    'registrar' => 'IE Domain Registry'
                    );
		return $r;
		}
	}
?>
