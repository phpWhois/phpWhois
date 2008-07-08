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

/* runic.whois	1.0	Vadim Smelyansky <vadim@vadiaz.com> 2007/01/25 */

if (!defined('__SU_HANDLER__'))
	define('__SU_HANDLER__', 1);

require_once('whois.parser.php');

class su_handler
	{

	function parse($data_str, $query)
		{
		$items = array(
                  'domain:' => 'domain.name',
                  'state:' => 'domain.status',
                  'person:' => 'owner.name',
                  'phone:' => 'owner.phone',
                  'e-mail:' => 'owner.email',
                  'created:' => 'domain.created',
                  'paid-till:' => 'domain.expires',
/*
                  'nserver:' => 'domain.nserver.',
                  'source:' => 'domain.source',
                  'type:' => 'owner.type',
                  'org:' => 'owner.organization',
                  'fax-no:' => 'owner.fax',
*/
		              );

		$r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'dmy');

		$r['regyinfo'] = array(
                            'referrer' => 'http://www.ripn.net',
                            'registrar' => 'RUCENTER-REG-RIPN'
                          );

		return ($r);
		}
	}

?>
