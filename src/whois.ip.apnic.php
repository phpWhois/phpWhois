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

require_once('whois.parser.php');

if(!defined('__APNIC_HANDLER__'))
  define('__APNIC_HANDLER__',1);

class apnic_handler
	{
	function parse ($data_str, $query)
		{
		$translate = array (
							'fax-no' => 'fax',
							'e-mail' => 'email',
							'nic-hdl' => 'handle',
							'person' => 'name',
							'country' => 'address',
							'netname' => 'name',
							'descr' => 'desc',
							'aut-num' => 'handle',
							'country' => 'country'
							  );

		$contacts = array (
							  'admin-c' => 'admin',
							  'tech-c' => 'tech'
							  );

		$blocks = generic_parser_a_blocks($data_str,$translate,$disclaimer);

		if (isset($disclaimer) && is_array($disclaimer)) $r['disclaimer'] = $disclaimer;

		if (empty($blocks) || !is_array($blocks['main']))
			{
			$r['registered'] = 'no';
			}
		else
			{
			if (isset($blocks[$query]))
				{
				$as = true;
				$rb = $blocks[$query];
				}
			else
				{
				$rb = $blocks['main'];
				$as = false;
				}

			$r['registered'] = 'yes';

			while (list($key,$val) = each($contacts))
				if (isset($rb[$key]))
					{
					if (is_array($rb[$key]))
						$blk = $rb[$key][count($rb[$key])-1];
					else
						$blk = $rb[$key];

					//$blk = strtoupper(strtok($blk,' '));
					if (isset($blocks[$blk])) $r[$val] = $blocks[$blk];
					unset($rb[$key]);
					}

			$r['network'] = $rb;
			format_dates($r,'Ymd');

			if (isset($r['network']['desc']))
				{
				if (is_array($r['network']['desc']))
					{
					$r['owner']['organization'] = array_shift($r['network']['desc']);
					$r['owner']['address'] = $r['network']['desc'];
					}
				else
					$r['owner']['organization'] = $r['network']['desc'];

				unset($r['network']['desc']);
				}

			if (isset($r['network']['address']))
				{
				if (isset($r['owner']['address']))
					$r['owner']['address'][] = $r['network']['address'];
				else
					$r['owner']['address'] = $r['network']['address'];

				unset($r['network']['address']);
				}
			}

		$r = array( 'regrinfo' => $r );
		$r['regyinfo']['type'] ='ip';
		$r['regyinfo']['registrar'] = 'Asia Pacific Network Information Centre';
		return $r;
		}
}
?>
