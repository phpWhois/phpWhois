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

/* esnic.whois	1.0  David Saez Padros <david@ols.es> */
/* esnic.whois  1.1  David Saez Padros <david@ols.es> */
/*
 * requires https support, please see:
 *
 * http://www.php.net/manual/en/ref.openssl.php
 * http://www.php.net/manual/en/wrappers.php
 *
 */

if (!defined('__ES_HANDLER__'))
	define('__ES_HANDLER__', 1);

require_once('whois.parser.php');

class es_handler
	{

	function parse($data_str, $query)
		{		
		$items = array(
                'domain.created' => 'Fecha Creación:',
				'domain.expires' => 'Fecha Expiración:',
                'owner.name' => 'Registrante:',
                'admin' => 'Contacto Administrativo:',
                'tech.handle' => 'Contacto Técnico:',
                'billing.handle' => 'Contacto Facturación:',
                'domain.nserver' => 'Servidores DNS:'
		            );

		array_shift($data_str['rawdata']);
		array_shift($data_str['rawdata']);
		
		$r['regrinfo'] = get_blocks($data_str['rawdata'], $items);
		
		if (!isset($r['regrinfo']['domain']['created']) || is_array($r['regrinfo']['domain']['created']))
			{
			$r['regrinfo'] = array ( 'registered' => 'no');
			$r['rawdata'] = $data_str['rawdata'];
			$r['rawdata'][] = 'Domain not found';
			return $r;
			}
		
		if (isset($r['regrinfo']['admin']))
			{
			$handle = $r['regrinfo']['admin'];
			$items['admin'].=' '.$r['regrinfo']['admin'];
			}

		$r['regrinfo'] = get_blocks($data_str['rawdata'], $items);
		
		$r['rawdata'] = $data_str['rawdata'];
		
		$r['regrinfo']['admin'] = get_contact($r['regrinfo']['admin']);
		$r['regrinfo']['admin']['handle'] = $handle;

		$r['regrinfo']['registered'] = 'yes';
			
		$r['regyinfo'] = array(
                'referrer' => 'http://www.nic.es',
                'registrar' => 'ES-NIC'
                );

		format_dates($r, 'ymd');
		return $r;
		}
	}

?>
