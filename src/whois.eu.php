<?php

/**
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
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
 * @link      http://phpwhois.pw
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 */

require_once('whois.parser.php');

if (!defined('__EU_HANDLER__')) {
    define('__EU_HANDLER__', 1);
}

class eu_handler
{
    function parse($data, $query)
    {
        $items = [
            'domain.name'      => 'Domain:',
            'domain.status'    => 'Status:',
            'domain.nserver'   => 'Name servers:',
            'domain.created'   => 'Registered:',
            'domain.registrar' => 'Registrar:',
            'tech'             => 'Registrar Technical Contacts:',
            'owner'            => 'Registrant:',
            ''                 => 'Please visit',
        ];

        $extra = [
            'organisation:' => 'organization',
            'website:'      => 'url',
        ];

        $r = [
            'rawdata' => $data['rawdata'],
        ];

        $r['regrinfo'] = get_blocks($data['rawdata'], $items);

        if (!empty($r['regrinfo']['domain']['status'])) {
            switch ($r['regrinfo']['domain']['status']) {
                case 'FREE':
                case 'AVAILABLE':
                    $r['regrinfo']['registered'] = 'no';
                    break;

                case 'APPLICATION PENDING':
                    $r['regrinfo']['registered'] = 'pending';
                    break;

                default:
                    $r['regrinfo']['registered'] = 'unknown';
            }
        } else {
            $r['regrinfo']['registered'] = 'yes';
        }

        if (isset($r['regrinfo']['tech'])) {
            $r['regrinfo']['tech'] = get_contact($r['regrinfo']['tech'], $extra);
        }

        if (isset($r['regrinfo']['domain']['registrar'])) {
            $r['regrinfo']['domain']['registrar'] = get_contact($r['regrinfo']['domain']['registrar'], $extra);
        }

        $r['regyinfo']['referrer']  = 'http://www.eurid.eu';
        $r['regyinfo']['registrar'] = 'EURID';

        return $r;
    }
}
