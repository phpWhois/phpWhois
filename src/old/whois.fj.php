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

if (!defined('__FJ_HANDLER__'))
    define('__FJ_HANDLER__', 1);

class fj_handler {

    function parse($data_str, $query) {
        $items = array(
            'owner' => 'Registrant:',
            'domain.status' => 'Status:',
            'domain.expires' => 'Expires:',
            'domain.nserver' => 'Domain servers:'
        );

        $r = array();
        $r['regrinfo'] = get_blocks($data_str['rawdata'], $items);

        if (!empty($r['regrinfo']['domain']['status'])) {
            $r['regrinfo'] = get_contacts($r['regrinfo']);

            date_default_timezone_set("Pacific/Fiji");

            if (isset($r['regrinfo']['domain']['expires']))
                $r['regrinfo']['domain']['expires'] = strftime("%Y-%m-%d", strtotime($r['regrinfo']['domain']['expires']));

            $r['regrinfo']['registered'] = 'yes';
        } else {
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo'] = array(
            'referrer' => 'http://www.domains.fj',
            'registrar' => 'FJ Domain Name Registry'
        );
        return $r;
    }

}
