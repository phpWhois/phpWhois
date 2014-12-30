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

if (!defined('__SU_HANDLER__'))
    define('__SU_HANDLER__', 1);

require_once('whois.parser.php');

class su_handler {

    function parse($data_str, $query) {
        $items = array(
            'domain:' => 'domain.name',
            'registrar:' => 'domain.sponsor',
            'state:' => 'domain.status',
            'person:' => 'owner.name',
            'phone:' => 'owner.phone',
            'e-mail:' => 'owner.email',
            'created:' => 'domain.created',
            'paid-till:' => 'domain.expires',
                /*
                  'nserver:' => 'domain.nserver.',
                  'source:' => 'domain.source',
                  'type:' => 'owner.type',
                  'org:' => 'owner.organization',
                  'fax-no:' => 'owner.fax',
                 */
        );

        $r = array();
        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'dmy');

        $r['regyinfo'] = array(
            'referrer' => 'http://www.ripn.net',
            'registrar' => 'RUCENTER-REG-RIPN'
        );
        return $r;
    }

}
