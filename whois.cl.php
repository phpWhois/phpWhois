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

if (!defined('__CL_HANDLER__'))
	define('__CL_HANDLER__', 1);

require_once('whois.parser.php');

class cl_handler
	{
	function parse($data_str, $query)
		{
		$items = array(
                  'admin' => '(Administrative Contact)',
                  'tech' => 'Contacto Técnico (Technical Contact):',
                  'domain.nserver' => 'Servidores de nombre (Domain servers):',
                  'domain.changed' => '(Database last updated on):'
                  );

		$trans = array(
					'organización:' => 'organization',
					'nombre      :' => 'name');

		$r['regrinfo'] = easy_parser($data_str['rawdata'], $items, 'd-m-y', $trans);
		$r['regyinfo'] = array(
                    'referrer' => 'http://www.nic.cl',
                    'registrar' => 'NIC Chile'
                    );
		return $r;
		}
	}
?>
