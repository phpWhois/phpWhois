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

if (!defined('__CZ_HANDLER__'))
	define('__CZ_HANDLER__', 1);

require_once('whois.parser.php');

class cz_handler
	{

	function parse($data_str, $query)
		{

		$translate = array(
                      'expire' 	=> 'expires',
                      'nserver' => 'nserver',
                      'domain' 	=> 'name',
                      'nic-hdl' => 'handle',
                      'reg-c'	=> '',
                      'descr'	=> 'desc',
                      'e-mail'	=> 'email',
                      'person'	=> 'name',
                      'role'	=> 'organization',
                      'fax-no'	=> 'fax'
		                  );

		$contacts = array(
                      'admin-c' => 'admin',
                      'tech-c' => 'tech',
                      'bill-c' => 'billing'
		                  );

		$r['regyinfo'] = array(
                          'referrer' => 'http://www.nic.cz',
                          'registrar' => 'CZ-NIC'
                          );

		$blocks = generic_parser_a_blocks($data_str['rawdata'], $translate, $disclaimer);

		if (isset($disclaimer) && is_array($disclaimer)) 
			$reg['disclaimer'] = $disclaimer;

		if (!isset($blocks) || !is_array($blocks['main']))
			{
			$reg['registered'] = 'no';
			$r['regrinfo'] = $reg;
			return ($r);
			}
		
		$reg['registered'] = 'yes';

		$reg['domain'] = $blocks['main'];	
		$reg['owner'] = $blocks[$blocks['main']['admin-c']];	
		
		if (isset($reg['owner']['admin-c']))
			{
			if (is_array($reg['owner']['admin-c']))
				$reg['admin'] = $blocks[$reg['owner']['admin-c'][0]];
			else
				$reg['admin'] = $blocks[$reg['owner']['admin-c']];
			}
		else
			$reg['admin'] = $reg['owner'];
			
		if (isset($blocks['main']['tech-c']))
			$tech = $blocks['main']['tech-c'];	
		else
			if (isset($reg['owner']['tech-c']))
				$tech = $reg['owner']['tech-c'];					
			else
				$tech = '';

		if ($tech != '')
			{
			if (is_array($tech))
				$reg['tech'] = $blocks[$tech[0]];
			else
				$reg['tech'] = $blocks[$tech];
			}
			
		if (isset($blocks['main']['bill-c']))
			$bill = $blocks['main']['bill-c'];	
		else
			if (isset($reg['owner']['bill-c']))
				$bill= $reg['owner']['bill-c'];					
			else
				$bill = '';

		if ($bill != '')
			{
			if (is_array($bill))
				$reg['bill'] = $blocks[$bill[0]];
			else
				$reg['bill'] = $blocks[$bill];
			}
			
		if (isset($reg['domain']['tech-c'])) unset($reg['domain']['tech-c']);
		if (isset($reg['domain']['admin-c'])) unset($reg['domain']['admin-c']);
		if (isset($reg['domain']['bill-c'])) unset($reg['domain']['bill-c']);
		
		if (isset($reg['owner']['tech-c'])) unset($reg['owner']['tech-c']);
		if (isset($reg['owner']['admin-c'])) unset($reg['owner']['admin-c']);
		if (isset($reg['owner']['bill-c'])) unset($reg['owner']['bill-c']);
		
		if (isset($reg['admin']['tech-c'])) unset($reg['admin']['tech-c']);
		if (isset($reg['admin']['admin-c'])) unset($reg['admin']['admin-c']);
		if (isset($reg['admin']['bill-c'])) unset($reg['admin']['bill-c']);

		if (isset($reg['tech']['tech-c'])) unset($reg['tech']['tech-c']);
		if (isset($reg['tech']['admin-c'])) unset($reg['tech']['admin-c']);
		if (isset($reg['tech']['bill-c'])) unset($reg['tech']['bill-c']);
		
		if (isset($reg['bill']))
			{
			if (isset($reg['bill']['tech-c'])) unset($reg['bill']['tech-c']);
			if (isset($reg['bill']['admin-c'])) unset($reg['bill']['admin-c']);
			if (isset($reg['bill']['bill-c'])) unset($reg['bill']['bill-c']);
			}

		format_dates($reg,'Ymd');
		$r['regrinfo'] = $reg;
		return ($r);
		}
	}
?>
