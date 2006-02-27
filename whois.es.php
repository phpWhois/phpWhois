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
                'domain.name' => 'Dominio:',
                'domain.created' => 'Fecha de registro:',
				'domain.expires' => 'Fecha de caducidad:',
                'domain.nserver.0' => 'DNS primaria:',
                'domain.nserver.1' => 'DNS secundaria:',
                'owner.name' => 'Registrante:',
                'admin' => 'Contacto administrativo:',
                'billing' => 'Contacto de cobro:',
                'tech' => 'Contacto técnico:'
		            );

		$extra = array(
                'e.:' => 'email',
                't.:' => 'phone',
                'f.:' => 'fax'
		            );

		$rawdata = array();
		$data_ok = false;
		$final = false;
		
		while (list($key, $val) = each($data_str['rawdata']))
			{
			if (substr($val,0,9)=='Dominio: ') 
				$data_ok = true;
			else
				if (!$data_ok) continue;
			
			if (substr($val,0,4)=='DNS ') $final = true;
			
			if ($val=='' && $final) break;
			
			$rawdata[] = $val;
			}

		$r['regrinfo'] = get_blocks($rawdata, $items);
		
		if (isset($r['regrinfo']['admin']))   $items['admin'].=' '.$r['regrinfo']['admin'];
		if (isset($r['regrinfo']['billing'])) $items['billing'].=' '.$r['regrinfo']['billing'];
		if (isset($r['regrinfo']['tech']))    $items['tech'].=' '.$r['regrinfo']['tech'];
		
		$r['regrinfo'] = get_blocks($rawdata, $items);
		
		$r['rawdata'] = $rawdata;
		
		if (isset($r['regrinfo']['domain']['name']))
			{
			$r['regrinfo']['admin'] = get_contact($r['regrinfo']['admin'], $extra);
			$r['regrinfo']['billing'] = get_contact($r['regrinfo']['billing'], $extra);
			$r['regrinfo']['tech'] = get_contact($r['regrinfo']['tech'], $extra);
			$r['regrinfo']['registered'] = 'yes';

			if (is_array($r['regrinfo']['domain']['nserver'][0]))
				unset($r['regrinfo']['domain']['nserver'][0]);
			}
		else
			{
			$r['regrinfo']['registered'] = 'no';
			$r['rawdata'][] = 'Domain not found';
			}
			
		$r['regyinfo'] = array(
                'referrer' => 'http://www.nic.es',
                'registrar' => 'ES-NIC'
                );

		format_dates($r, 'ymd');
		return $r;
		}
	}

?>
