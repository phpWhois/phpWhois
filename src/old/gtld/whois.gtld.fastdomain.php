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

if (!defined('__FASTDOMAIN_HANDLER__'))
    define('__FASTDOMAIN_HANDLER__', 1);

require_once('whois.parser.php');

class fastdomain_handler {

    function parse($data_str, $query) {
        $items = array(
            'owner' => 'Registrant Info:',
            'admin' => 'Administrative Info:',
            'tech' => 'Technical Info:',
            'domain.name' => 'Domain Name:',
            'domain.sponsor' => 'Provider Name....:',
            'domain.referrer' => 'Provider Homepage:',
            'domain.nserver' => 'Domain servers in listed order:',
            'domain.created' => 'Created on..............:',
            'domain.expires' => 'Expires on..............:',
            'domain.changed' => 'Last modified on........:',
            'domain.status' => 'Status:'
        );

        while (list($key, $val) = each($data_str)) {
            $faststr = strpos($val, ' (FAST-');
            if ($faststr)
                $data_str[$key] = substr($val, 0, $faststr);
        }

        $r = easy_parser($data_str, $items, 'dmy', array(), false, true);

        if (isset($r['domain']['sponsor']) && is_array($r['domain']['sponsor']))
            $r['domain']['sponsor'] = $r['domain']['sponsor'][0];

        if (isset($r['domain']['nserver'])) {
            reset($r['domain']['nserver']);

            while (list($key, $val) = each($r['domain']['nserver'])) {
                if ($val == '=-=-=-=')
                    unset($r['domain']['nserver'][$key]);
            }
        }

        return $r;
    }

}
