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

if (!defined('__JP_HANDLER__'))
	define('__JP_HANDLER__', 1);

require_once('whois.parser.php');

class jp_handler extends WhoisClient
	{
	function parse($data_str, $query)
		{
		$items = array(
						'[State]' => 'domain.status',
						'[Status]' => 'domain.status',
						'[Registered Date]' => 'domain.created',
						'[Created on]' => 'domain.created',
						'[Expires on]' => 'domain.expires',
						'[Last Updated]' => 'domain.changed',
						'[Last Update]' => 'domain.changed',
						'[Organization]' => 'owner.organization',
						'[Name]' => 'owner.name',
						'[Email]' => 'owner.email',
						'[Postal code]' => 'owner.address.pcode',
						'[Postal Address]' => 'owner.address.street',
						'[Phone]' => 'owner.phone',
						'[Fax]' => 'owner.fax',
						'[Administrative Contact]' => 'admin.handle',
						'[Technical Contact]' => 'tech.handle',
						'[Name Server]' => 'domain.nserver.'
						);

		$r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'ymd');

		$r['regyinfo'] = array(
                          'referrer' => 'http://www.jprs.jp',
                          'registrar' => 'Japan Registry Services'
                          );

		if (!$this->deep_whois) return $r;

		$r['rawdata'] = $data_str['rawdata'];

		$items = array(
					'a. [JPNIC Handle]'	=> 'handle',
					'c. [Last, First]'	=> 'name',
					'd. [E-Mail]'		=> 'email',
					'g. [Organization]' => 'organization',
					'o. [TEL]'			=> 'phone',
					'p. [FAX]'			=> 'fax',
					'[Last Update]'		=> 'changed'
					);

		$this->Query['server'] = 'jp.whois-servers.net';

		if (!empty($r['regrinfo']['admin']['handle']))
			{
			$rwdata = $this->GetRawData('CONTACT '.$r['regrinfo']['admin']['handle'].'/e');
			$r['rawdata'][] = '';
			$r['rawdata'] = array_merge($r['rawdata'],$rwdata);
			$r['regrinfo']['admin'] = generic_parser_b($rwdata,$items,'ymd',false);
			$r = $this->set_whois_info($r);
			}

		if (!empty($r['regrinfo']['tech']['handle']))
			{
			if (!empty($r['regrinfo']['admin']['handle']) &&
				$r['regrinfo']['admin']['handle'] == $r['regrinfo']['tech']['handle'])
				{
				$r['regrinfo']['tech'] = $r['regrinfo']['admin'];
				}
			else
				{
				unset($this->Query);
				$this->Query['server'] = 'jp.whois-servers.net';
				$rwdata = $this->GetRawData('CONTACT '.$r['regrinfo']['tech']['handle'].'/e');
				$r['rawdata'][] = '';
				$r['rawdata'] = array_merge($r['rawdata'],$rwdata);
				$r['regrinfo']['tech'] = generic_parser_b($rwdata,$items,'ymd',false);
				$r = $this->set_whois_info($r);
				}
			}

		return $r;
		}
	}
?>