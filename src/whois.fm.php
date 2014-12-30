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

if (!defined('__FM_HANDLER__'))
    define('__FM_HANDLER__', 1);

require_once('whois.parser.php');

class fm_handler {

    function parse($data, $query) {
        $items = array(
            'owner' => 'Registrant',
            'admin' => 'Admin',
            'tech' => 'Technical',
            'billing' => 'Billing',
            'domain.nserver' => 'Name Servers:',
            'domain.created' => 'Created:',
            'domain.expires' => 'Expires:',
            'domain.changed' => 'Modified:',
            'domain.status' => 'Status:',
            'domain.sponsor' => 'Registrar Name:'
        );

        $r = array();
        $r['regrinfo'] = get_blocks($data['rawdata'], $items);

        $items = array(
            'phone number:' => 'phone',
            'email address:' => 'email',
            'fax number:' => 'fax',
            'organisation:' => 'organization'
        );

        if (!empty($r['regrinfo']['domain']['created'])) {
            $r['regrinfo'] = get_contacts($r['regrinfo'], $items);

            if (count($r['regrinfo']['billing']['address']) > 4)
                $r['regrinfo']['billing']['address'] = array_slice($r['regrinfo']['billing']['address'], 0, 4);

            $r['regrinfo']['registered'] = 'yes';
            format_dates($r['regrinfo']['domain'], 'dmY');
        }
        else {
            $r = '';
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo']['referrer'] = 'http://www.dot.dm';
        $r['regyinfo']['registrar'] = 'dotFM';
        return $r;
    }

}
