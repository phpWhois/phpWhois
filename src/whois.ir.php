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

// Define the handler flag.
if (!defined('__IR_HANDLER__'))
    define('__IR_HANDLER__', 1);

// Loadup the parser.
require_once('whois.parser.php');

/**
 * IR Domain names lookup handler class.
 */
class ir_handler {

    function parse($data_str, $query) {
        $translate = array(
            'nic-hdl' => 'handle',
            'org' => 'organization',
            'e-mail' => 'email',
            'person' => 'name',
            'fax-no' => 'fax',
            'domain' => 'name'
        );

        $contacts = array(
            'admin-c' => 'admin',
            'tech-c' => 'tech',
            'holder-c' => 'owner'
        );

        $reg = generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');

        $r = array();
        $r['regrinfo'] = $reg;
        $r['regyinfo'] = array(
            'referrer' => 'http://whois.nic.ir/',
            'registrar' => 'NIC-IR'
        );
        return $r;
    }

}
