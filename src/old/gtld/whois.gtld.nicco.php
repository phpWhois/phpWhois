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

if (!defined('__NICCO_HANDLER__'))
    define('__NICCO_HANDLER__', 1);

require_once('whois.parser.php');

class nicco_handler {

    function parse($data_str, $query) {
        $items = array(
            'owner' => 'Holder Contact',
            'admin' => 'Admin Contact',
            'tech' => 'Tech. Contact',
            'domain.nserver.' => 'Nameservers',
            'domain.created' => 'Creation Date:',
            'domain.expires' => 'Expiration Date:'
        );

        $translate = array(
            'city:' => 'address.city',
            'org. name:' => 'organization',
            'address1:' => 'address.street.',
            'address2:' => 'address.street.',
            'state:' => 'address.state',
            'postal code:' => 'address.zip'
        );

        $r = get_blocks($data_str, $items, true);
        $r['owner'] = get_contact($r['owner'], $translate);
        $r['admin'] = get_contact($r['admin'], $translate, true);
        $r['tech'] = get_contact($r['tech'], $translate, true);
        return format_dates($r, 'dmy');
    }

}
