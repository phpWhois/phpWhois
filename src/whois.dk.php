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
 * @copyright Copyright (c) 2015 Kim Hemsø Rasmussen
 */

require_once('whois.parser.php');

if (!defined('__DK_HANDLER__')) {
    define('__DK_HANDLER__', 1);
}

/**
 * @author Kim Hemsø Rasmussen <kimhemsoe@gmail.com>
 */
class dk_handler
{
    public function parse($data, $query)
    {
        $r = array();

        $items = array(
            'domain.name' => 'Domain:',
            'domain.status' => 'Status:',
            'domain.created' => 'Registered:',
            'domain.expires' => 'Expires:',
            'domain.dnssec' => 'Dnssec:',
            'domain.nserver' => 'Nameservers',
            'tech' => 'Administrator',
            'owner' => 'Registrant',
            'disclaimer.' => '#',
        );

        $r['regrinfo'] = get_blocks($data['rawdata'], $items);

        $contactFields = array(
            'handle:' => 'handle',
            'city:' => 'address.city',
            'postalcode:' => 'address.pcode',
        );

        $r['regrinfo'] = get_contacts($r['regrinfo'], $contactFields);

        return $r;
    }
}
