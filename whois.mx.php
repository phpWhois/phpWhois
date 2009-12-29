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

/* mxnic.whois	1.0	Torfinn Nome <torfinn@nome.no> 2003-02-15 */
/* Based upon info.whois by David Saez Padros <david@ols.es> */

if (!defined('__MX_HANDLER__'))
	define('__MX_HANDLER__', 1);

require_once('whois.parser.php');

class mx_handler
	{

	function parse($data_str, $query)
		{
		$items = array(
						'owner'	=> 'Registrant:',
						'admin'	=> 'Administrative Contact:',
						'tech'	=> 'Technical Contact:',
						'billing' => 'Billing Contact:',						
						'domain.nserver' => 'Name Servers:',
						'domain.created' => 'Created On:',
						'domain.expires' => 'Expiration Date:',
						'domain.changed' => 'Last Updated On:',
						'domain.sponsor' => 'Registrar:'
						);

		$extra = array(
						'city:' => 'address.city',
						'state:'	=> 'address.state',
						'dns:'	=> '0'
						);

		$r['regrinfo'] = easy_parser($data_str['rawdata'],$items,'dmy',$extra);
		
		$r['regyinfo'] = array(
                  'registrar' => 'NIC Mexico',
                  'referrer' => 'http://www.nic.mx/'
                  );
		
		if (empty($r['regrinfo']['domain']['created']))
			$r['regrinfo']['registered'] = 'no';
		else
			$r['regrinfo']['registered'] = 'yes';

		return ($r);
		}

	}

?>
