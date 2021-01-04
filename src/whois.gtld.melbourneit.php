<?php

/**
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @license
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @link http://phpwhois.pw
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 */

require_once('whois.parser.php');

if (!defined('__MELBOURNEIT_HANDLER__')) {
    define('__MELBOURNEIT_HANDLER__', 1);
}

class melbourneit_handler
{

    function parse($data_str, $query)
    {
        $items = array(
            'Domain Name..........' => 'domain.name',
            'Registration Date....' => 'domain.created',
            'Expiry Date..........' => 'domain.expires',
            'Organisation Name....' => 'owner.name',
            'Organisation Address.' => 'owner.address.',
            'Admin Name...........' => 'admin.name',
            'Admin Address........' => 'admin.address.',
            'Admin Email..........' => 'admin.email',
            'Admin Phone..........' => 'admin.phone',
            'Admin Fax............' => 'admin.fax',
            'Tech Name............' => 'tech.name',
            'Tech Address.........' => 'tech.address.',
            'Tech Email...........' => 'tech.email',
            'Tech Phone...........' => 'tech.phone',
            'Tech Fax.............' => 'tech.fax',
            'Name Server..........' => 'domain.nserver.'
        );

        return generic_parser_b($data_str, $items, 'ymd');
    }
}
