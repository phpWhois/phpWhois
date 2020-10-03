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

if (!defined('__WS_HANDLER__')) {
    define('__WS_HANDLER__', 1);
}

use phpWhois\WhoisClient;

require_once('whois.parser.php');

class ws_handler extends WhoisClient
{
    function parse($data_str, $query)
    {
        $items = [
            'Domain Name:'                            => 'domain.name',
            'Creation Date:'                          => 'domain.created',
            'Updated Date:'                           => 'domain.changed',
            'Registrar Registration Expiration Date:' => 'domain.expires',
            'Registrar:'                              => 'domain.sponsor',
            'WHOIS Server:'                           => 'rwhois',
            'Domain Status:'                          => 'domain.status.',
            'Registrant Name:'                        => 'owner.name',
            'Registrant Organization:'                => 'owner.organization',
            'Registrant Street:'                      => 'owner.address.address.0',
            'Registrant City:'                        => 'owner.address.city',
            'Registrant State/Province:'              => 'owner.address.state',
            'Registrant Postal Code:'                 => 'owner.address.pcode',
            'Registrant Country:'                     => 'owner.address.country',
            'Registrant Phone:'                       => 'owner.phone',
            'Registrant Fax:'                         => 'owner.fax',
            'Registrant Email:'                       => 'owner.email',
            'Domain Created:'                         => 'domain.created',
            'Admin Name:'                             => 'admin.name',
            'Domain Last Updated:'                    => 'domain.changed',
            'Admin Organization:'                     => 'admin.organization',
            'Registrar Name:'                         => 'domain.sponsor',
            'Admin Street:'                           => 'admin.address.address.0',
            'Current Nameservers:'                    => 'domain.nserver.',
            'Admin City:'                             => 'admin.address.city',
            'Administrative Contact Email:'           => 'admin.email',
            'Admin State/Province:'                   => 'admin.address.state',
            'Administrative Contact Telephone:'       => 'admin.phone',
            'Admin Postal Code:'                      => 'admin.address.pcode',
            'Registrar Whois:'                        => 'rwhois',
            'Admin Country:'                          => 'admin.address.country',
            'Admin Phone:'                            => 'admin.phone',
            'Admin Fax:'                              => 'admin.fax',
            'Admin Email:'                            => 'admin.email',
            'Tech Name:'                              => 'tech.name',
            'Tech Organization:'                      => 'tech.organization',
            'Tech Street:'                            => 'tech.address.address.0',
            'Tech City:'                              => 'tech.address.city',
            'Tech State/Province:'                    => 'tech.address.state',
            'Tech Postal Code:'                       => 'tech.address.pcode',
            'Tech Country:'                           => 'tech.address.country',
            'Tech Phone:'                             => 'tech.phone',
            'Tech Fax:'                               => 'tech.fax',
            'Tech Email:'                             => 'tech.email',
            'Name Server:'                            => 'domain.nserver.',
        ];

        $r = [
            'rawdata' => $data_str['rawdata'],
        ];

        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'ymd');

        $r['regyinfo']['referrer']  = 'https://www.samoanic.ws';
        $r['regyinfo']['registrar'] = 'Samoa Nic';

        if (!empty($r['regrinfo']['domain']['name'])) {
            $r['regrinfo']['registered'] = 'yes';

            if (isset($r['regrinfo']['rwhois'])) {
                if ($this->deepWhois) {
                    $r['regyinfo']['whois'] = $r['regrinfo']['rwhois'];

                    $r = $this->deepWhois($query, $r);
                }

                unset($r['regrinfo']['rwhois']);
            }
        } else {
            $r['regrinfo']['registered'] = 'no';
        }

        return $r;
    }
}
