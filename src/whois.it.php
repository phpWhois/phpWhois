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

/**
 * @todo BUG
 * - nserver -> array
 * - ContactID in address
 */

if (!defined('__IT_HANDLER__'))
    define('__IT_HANDLER__', 1);

require_once('whois.parser.php');

class it_handler {

    function parse($data_str, $query) {
        $items = array(
            'domain.name' => 'Domain:',
            'domain.nserver' => 'Nameservers',
            'domain.status' => 'Status:',
            'domain.expires' => 'Expire Date:',
            'owner' => 'Registrant',
            'admin' => 'Admin Contact',
            'tech' => 'Technical Contacts',
            'registrar' => 'Registrar'
        );

        $extra = array(
            'address:' => 'address.',
            'contactid:' => 'handle',
            'organization:' => 'organization',
            'created:' => 'created',
            'last update:' => 'changed',
            'web:' => 'web'
        );

        $r = array();
        $r['regrinfo'] = easy_parser($data_str['rawdata'], $items, 'ymd', $extra);

        if (isset($r['regrinfo']['registrar'])) {
            $r['regrinfo']['domain']['registrar'] = $r['regrinfo']['registrar'];
            unset($r['regrinfo']['registrar']);
        }

        $r['regyinfo'] = array(
            'registrar' => 'IT-Nic',
            'referrer' => 'http://www.nic.it/'
        );
        return $r;
    }

}
