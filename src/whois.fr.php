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

if (!defined('__FR_HANDLER__'))
    define('__FR_HANDLER__', 1);

require_once('whois.parser.php');

class fr_handler {

    function parse($data_str, $query) {
        $translate = array(
            'fax-no' => 'fax',
            'e-mail' => 'email',
            'nic-hdl' => 'handle',
            'ns-list' => 'handle',
            'person' => 'name',
            'address' => 'address.',
            'descr' => 'desc',
            'anniversary' => '',
            'domain' => '',
            'last-update' => 'changed',
            'registered' => 'created',
            'country' => 'address.country',
            'registrar' => 'sponsor',
            'role' => 'organization'
        );

        $contacts = array(
            'admin-c' => 'admin',
            'tech-c' => 'tech',
            'zone-c' => 'zone',
            'holder-c' => 'owner',
            'nsl-id' => 'nserver'
        );

        $reg = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'dmY');

        if (isset($reg['nserver'])) {
            $reg['domain'] = array_merge($reg['domain'], $reg['nserver']);
            unset($reg['nserver']);
        }

        $r = array();
        $r['regrinfo'] = $reg;
        $r['regyinfo'] = array(
            'referrer' => 'http://www.nic.fr',
            'registrar' => 'AFNIC'
        );
        return $r;
    }

}
