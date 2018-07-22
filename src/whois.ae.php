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

if (!defined('__AE_HANDLER__')) {
    define('__AE_HANDLER__', 1);
}

require_once('whois.parser.php');

class ae_handler
{

    function parse($data_str, $query)
    {
        $items = [
            'Domain Name:'                     => 'domain.name',
            'Registrar Name:'                  => 'domain.sponsor',
            'Status:'                          => 'domain.status',
            'Registrant Contact ID:'           => 'owner.handle',
            'Registrant Contact Name:'         => 'owner.name',
            'Registrant Contact Organisation:' => 'owner.organization',
            'Tech Contact Name:'               => 'tech.name',
            'Tech Contact ID:'                 => 'tech.handle',
            'Tech Contact Organisation:'       => 'tech.organization',
            'Name Server:'                     => 'domain.nserver.',
        ];

        $r = [
            'regrinfo' => generic_parser_b($data_str['rawdata'], $items, 'ymd'),
            'regyinfo' => [
                'referrer'  => 'http://www.nic.ae',
                'registrar' => 'UAENIC',
            ],
            'rawdata'  => $data_str['rawdata'],
        ];

        return $r;
    }

}
