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

if (!defined('__DK_HANDLER__')) {
    define('__DK_HANDLER__', 1);
}

require_once('whois.parser.php');

class dk_handler {

    function parse($data_str, $query) {

        $translate = [
            'Name' => 'name',
            'Address' => 'address.street',
            'City' => 'address.city',
            'Postalcode' => 'address.pcode',
            'Country' => 'address.country'
        ];

        $blocks = generic_parser_a_blocks($data_str['rawdata'], $translate, $disclaimer);

        $reg = [];
        if (isset($disclaimer) && is_array($disclaimer)) {
            $reg['disclaimer'] = $disclaimer;
        }

        if (empty($blocks) || !is_array($blocks['main'])) {
            $reg['registered'] = 'no';
        } else {
            $r = $blocks['main'];
            $reg['registered'] = 'yes';

            $ownerHandlePos = array_search('Registrant', $data_str['rawdata']) + 1;
            $ownerHandle = trim(substr(strstr($data_str['rawdata'][$ownerHandlePos], ':'), 1));

            $adminHandlePos = array_search('Administrator', $data_str['rawdata']) + 1;
            $adminHandle = trim(substr(strstr($data_str['rawdata'][$adminHandlePos], ':'), 1));

            $contacts = [
                'owner' => $ownerHandle,
                'admin' => $adminHandle,
            ];

            foreach ($contacts as $key => $val) {
                $blk = strtoupper(strtok($val, ' '));
                if (isset($blocks[$blk])) {
                    $reg[$key] = $blocks[$blk];
                }
            }

            $reg['domain'] = $r;

            format_dates($reg, 'Ymd');

        }

        $r = [];
        $r['rawdata'] = $data_str['rawdata'];
        $r['regrinfo'] = $reg;
        $r['regyinfo'] = [
            'referrer' => 'https://www.dk-hostmaster.dk/',
            'registrar' => 'DK Hostmaster'
        ];

        return $r;
    }

}
