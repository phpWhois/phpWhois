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

if (!defined('__RIPE_HANDLER__'))
    define('__RIPE_HANDLER__', 1);

class ripe_handler {

    function parse($data_str, $query) {
        $translate = array(
            'fax-no' => 'fax',
            'e-mail' => 'email',
            'nic-hdl' => 'handle',
            'person' => 'name',
            'netname' => 'name',
            'descr' => 'desc'
        );

        $contacts = array(
            'admin-c' => 'admin',
            'tech-c' => 'tech'
        );

        if (!empty($data_str['rawdata']))
            $data_str = $data_str['rawdata'];

        $r = generic_parser_a($data_str, $translate, $contacts, 'network');

        if (isset($r['network']['desc'])) {
            $r['owner']['organization'] = $r['network']['desc'];
            unset($r['network']['desc']);
        }

        if (isset($r['admin']['abuse-mailbox'])) {
            $r['abuse']['email'] = $r['admin']['abuse-mailbox'];
            unset($r['admin']['abuse-mailbox']);
        }

        if (isset($r['tech']['abuse-mailbox'])) {
            $r['abuse']['email'] = $r['tech']['abuse-mailbox'];
            unset($r['tech']['abuse-mailbox']);
        }

        // Clean mess
        if (isset($r['tech']['tech-c']))
            unset($r['tech']['tech-c']);
        if (isset($r['tech']['admin-c']))
            unset($r['tech']['admin-c']);
        if (isset($r['admin']['tech-c']))
            unset($r['admin']['tech-c']);
        if (isset($r['admin']['admin-c']))
            unset($r['admin']['admin-c']);

        $r = array('regrinfo' => $r);
        $r['regyinfo']['type'] = 'ip';
        $r['regyinfo']['registrar'] = 'RIPE Network Coordination Centre';
        return $r;
    }

}
