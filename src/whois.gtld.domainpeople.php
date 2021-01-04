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

if (!defined('__DOMAINPEOPLE_HANDLER__')) {
    define('__DOMAINPEOPLE_HANDLER__', 1);
}

require_once('whois.parser.php');

class domainpeople_handler
{

    function parse($data_str, $query)
    {

        $items = array(
            'owner' => 'Registrant Contact:',
            'admin' => 'Administrative Contact:',
            'tech' => 'Technical Contact:',
            'domain.name' => 'Domain name:',
            'domain.sponsor' => 'Registration Service Provided By:',
            'domain.referrer' => 'Contact:',
            'domain.nserver.' => 'Name Servers:',
            'domain.created' => 'Creation date:',
            'domain.expires' => 'Expiration date:',
//            'domain.changed' => 'Record last updated on',
            'domain.status' => 'Status:'
        );

        $r = easy_parser($data_str, $items, 'dmy', array(), false, true);
        if (isset($r['domain']['sponsor']) && is_array($r['domain']['sponsor'])) {
            $r['domain']['sponsor'] = $r['domain']['sponsor'][0];
        }
        return $r;
    }
}
