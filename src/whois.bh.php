<?php
/*
Whois.php        PHP classes to conduct whois queries

Copyright (C) 2011 Bernhard Reutner Fischer <rep.dot.nop@gmail.com>

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

if (!defined('__BH_HANDLER__'))
	define('__BH_HANDLER__', 1);

require_once('whois.parser.php');

class bh_handler
	{
	function parse($data_str, $query)
		{
		$items = array(
		    'Sponsoring Registrar Name:' => 'domain.sponsor.name',
		    'Sponsoring Registrar Email:' => 'domain.sponsor.email',
		    'Sponsoring Registrar Uri:' => 'domain.sponsor.uri',
		    'Sponsoring Registrar Phone:' => 'domain.sponsor.phone'
	        );
		$i = generic_parser_b($data_str['rawdata'], $items);
		$r['regrinfo'] = generic_parser_b($data_str['rawdata']);
		if (isset($r['regrinfo']['domain'])
		    && is_array($r['regrinfo']['domain']))
		    $r['regrinfo']['domain']['sponsor'] = $i['domain']['sponsor'];
		if (empty($r['regrinfo']['domain']['created']))
		    $r['regrinfo']['registered'] = 'no';
		else
		    $r['regrinfo']['registered'] = 'yes';
		$r['regyinfo'] = array(
                    'referrer' => 'http://www.nic.bh/',
                    'registrar' => 'NIC-BH'
                    );
		return $r;
		}
	}
?>