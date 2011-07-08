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

if (!defined('__INTERDOMAIN_HANDLER__'))
	define('__INTERDOMAIN_HANDLER__', 1);

require_once('whois.parser.php');

class interdomain_handler
	{
	function parse($data_str, $query)
		{
		$items = array(
                    'Domain Name................' => 'domain.name',
                    'Creation Date............' => 'domain.created',
                    'Expiry Date..............' => 'domain.expires',
                    'Last Update Date.........' => 'domain.changed',
                    'Name Server.............' => 'domain.nserver.',
                    'Organization Name........' => 'owner.name',
                    'Organization Org.........' => 'owner.organization',
                    'Organization Street......' => 'owner.address.street',
                    'Organization City........' => 'owner.address.city',
                    'Organization State.......' => 'owner.address.state',
                    'Organization PC..........' => 'owner.address.pcode',
                    'Organization Country.....' => 'owner.address.country',
                    'Organization Phone.......' => 'owner.phone',
                    'Organization e-mail......' => 'owner.email',
                    'Organization Contact Id....' => 'owner.handle',
                    'Administrative Contact Id..' => 'admin.handle',
                    'Administrative Name......' => 'admin.name',
                    'Administrative Org.......' => 'admin.organization',
                    'Administrative Street....' => 'admin.address.street',
                    'Administrative City......' => 'admin.address.city',
                    'Administrative State.....' => 'admin.address.state',
                    'Administrative PC........' => 'admin.address.pcode',
                    'Administrative Country...' => 'admin.address.country',
                    'Administrative Phone.....' => 'admin.phone',
                    'Administrative e-mail....' => 'admin.email',
                    'Administrative Fax.......' => 'admin.fax',
                    'Technical Contact Id.......' => 'tech.handle',
                    'Technical Name...........' => 'tech.name',
                    'Technical Org............' => 'tech.organization',
                    'Technical Street.........' => 'tech.address.street',
                    'Technical City...........' => 'tech.address.city',
                    'Technical State..........' => 'tech.address.state',
                    'Technical PC.............' => 'tech.address.pcode',
                    'Technical Country........' => 'tech.address.country',
                    'Technical Phone..........' => 'tech.phone',
                    'Technical e-mail.........' => 'tech.email',
                    'Technical Fax............' => 'tech.fax'
		              );

		return generic_parser_b($data_str, $items, 'dmy');
		}
	}
?>