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
                'domain.name' => 'Datos del Dominio',
                'domain.status' => 'Estado ',
                'domain.created' => 'Fecha de Alta ',
				'domain.expires' => 'Fecha Caducidad ',
                'domain.nserver' => 'Nombre Servidor  IP',
                'domain.sponsor' => 'Agente	Registrador',
                'owner' => 'PROPIETARIO DEL DOMINIO',
                'admin' => 'PERSONA DE CONTACTO ADMINISTRATIVO',
                'billing' => 'PERSONA DE CONTACTO DE FACTURACION',
                'tech' => 'PERSONA DE CONTACTO TECNICO'
		            );

		$extra = array(
                'domicilio' => 'address.street',
                'población' => 'address.city',
				'provincia' => '',
                'código postal' => 'address.pcode',
                'país' => 'address.country',
                'nic_handle' => 'handle',
                'nombre' =>	'name',
                'organización ' => 'organization',
                'tipo de titular'	=> '',
                'titular' => 'organization',
                'teléfono' => 'phone'
		            );

		while (list($key, $val) = each($data_str['rawdata']))
			{
			if (strpos($val, 'Nombre del dominio') !== false)
				{
				$data_str['rawdata'][$key] = 'PROPIETARIO DEL DOMINIO:';
				break;
				}
			}

		$rawdata = implode("\n", $data_str['rawdata']);
		$rawdata = str_replace('CONTACTO ADMINISTRATIVO', 'CONTACTO ADMINISTRATIVO:', $rawdata);
		$rawdata = explode("\n", $rawdata);

		$r['regrinfo'] = get_blocks($rawdata, $items);

		if (isset($r['regrinfo']['domain']['name']))
			{
			$r['regrinfo']['owner'] = get_contact($r['regrinfo']['owner'], $extra);
			$r['regrinfo']['admin'] = get_contact($r['regrinfo']['admin'], $extra);
			$r['regrinfo']['billing'] = get_contact($r['regrinfo']['billing'], $extra);
			$r['regrinfo']['tech'] = get_contact($r['regrinfo']['tech'], $extra);
			$r['regrinfo']['registered'] = 'yes';
			}
		else
			$r['regrinfo']['registered'] = 'no';

		$r['regyinfo'] = array(
                'referrer' => 'http://www.nic.es',
                'registrar' => 'ES-NIC'
                );

		$rawdata = implode("\n", $rawdata);
		$first = strpos($rawdata, 'Datos del Dominio '.$query);

		if ($first !== false)
			$rawdata = substr($rawdata, $first);

		$r['rawdata'] = explode("\n", $rawdata);
		format_dates($r, 'ymd');
		return $r;
		}
	}

?>
