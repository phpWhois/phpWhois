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

if (!defined('__DE_HANDLER__'))
	define('__DE_HANDLER__', 1);

require_once('whois.parser.php');

class de_handler
	{
	function parse($data_str, $query)
		{
		$items = array(
			'domain.name' =>	'Domain:',
			'domain.nserver.' =>'Nserver:',
			'domain.nserver.#' =>'Nsentry:',
			'domain.status' =>	'Status:',
			'domain.changed' =>	'Changed:',
			'domain.desc.' =>	'Descr:',
			'owner' =>	'[Holder]',
			'admin' =>	'[Admin-C]',
			'tech' =>	'[Tech-C]',
			'zone' =>	'[Zone-C]'
		            );

		$extra = array(
			'city:' => 'address.city',
			'postalcode:' => 'address.pcode',
			'countrycode:' => 'address.country',
			'remarks:' => '',
			'sip:' => 'sip',
			'type:'	=> ''
		            );

		$r['regrinfo'] = easy_parser($data_str['rawdata'], $items, 'ymd',$extra);

		$r['regyinfo'] = array(
                  'registrar' => 'DENIC eG',
                  'referrer' => 'http://www.denic.de/'
                  );

		 if (!isset($r['regrinfo']['domain']['status']) || $r['regrinfo']['domain']['status'] == "free")
			{
            $r['regrinfo']['registered'] = 'no';
            }
         else
			{
			$r['regrinfo']['domain']['changed'] = substr($r['regrinfo']['domain']['changed'], 0, 10);
			$r['regrinfo']['registered'] = 'yes';
			}
		return $r;
		}
	}
?>