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

if (!defined('__BE_HANDLER__'))
    define('__BE_HANDLER__', 1);

class be_handler {

    function parse($data, $query) {
        $items = array(
            'domain.name' => 'Domain:',
            'domain.status' => 'Status:',
            'domain.nserver' => 'Nameservers:',
            'domain.created' => 'Registered:',
            'owner' => 'Licensee:',
            'admin' => 'Onsite Contacts:',
            'tech' => 'Registrar Technical Contacts:',
            'agent' => 'Registrar:',
            'agent.uri' => 'Website:'
        );

        $trans = array(
            'company name2:' => ''
        );

        $r = array();

        $r['regrinfo'] = get_blocks($data['rawdata'], $items);

        if ($r['regrinfo']['domain']['status'] == 'REGISTERED') {
            $r['regrinfo']['registered'] = 'yes';
            $r['regrinfo'] = get_contacts($r['regrinfo'], $trans);

            if (isset($r['regrinfo']['agent'])) {
                $sponsor = get_contact($r['regrinfo']['agent'], $trans);
                unset($r['regrinfo']['agent']);
                $r['regrinfo']['domain']['sponsor'] = $sponsor;
            }

            $r = format_dates($r, '-mdy');
        } else
            $r['regrinfo']['registered'] = 'no';

        $r['regyinfo']['referrer'] = 'http://www.domain-registry.nl';
        $r['regyinfo']['registrar'] = 'DNS Belgium';
        return $r;
    }

}
