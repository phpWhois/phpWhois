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

if (!defined('__WS_HANDLER__'))
  define('__WS_HANDLER__', 1);

require_once('whois.parser.php');

class ws_handler extends WhoisClient
  {
  function parse($data_str, $query)
    {
    $items = array(
				'Domain Name:' 						=> 'domain.name',
				'Registrant Name:' 					=> 'owner.organization',
				'Registrant Email:' 				=> 'owner.email',
				'Domain Created:' 					=> 'domain.created',
				'Domain Last Updated:' 				=> 'domain.changed',
				'Registrar Name:'					=> 'domain.sponsor',
				'Current Nameservers:' 				=> 'domain.nserver.',
				'Administrative Contact Email:' 	=> 'admin.email',
				'Administrative Contact Telephone:' => 'admin.phone',
				'Registrar Whois:'					=> 'rwhois'
				);

	$r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'ymd');

    $r['regyinfo']['referrer'] = 'http://www.samoanic.ws';
    $r['regyinfo']['registrar'] = 'Samoa Nic';

    if (!empty($r['regrinfo']['domain']['name']))
		{
		$r['regrinfo']['registered'] = 'yes';

		if (isset($r['regrinfo']['rwhois']))
			{
			if ($this->deep_whois)
				{
				$r['regyinfo']['whois']	= $r['regrinfo']['rwhois'];
				$r = $this->DeepWhois($query,$r);
				}

			unset($r['regrinfo']['rwhois']);
			}
		}
    else
		$r['regrinfo']['registered'] = 'no';

    return $r;
    }
  }
?>