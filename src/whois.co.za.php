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

if (!defined('__CO_ZA_HANDLER__')) {
    define('__CO_ZA_HANDLER__', 1);
}

require_once('whois.parser.php');

class co_Za_handler
{

    function parse($data_str, $query)
    {
        $items = array(
            '0a. lastupdate             :' => 'domain.changed',
            '1a. domain                 :' => 'domain.name',
            '2b. registrantpostaladdress:' => 'owner.address.address.0',
            '2f. billingaccount         :' => 'billing.name',
            '2g. billingemail           :' => 'billing.email',
            '2i. invoiceaddress         :' => 'billing.address',
            '2j. registrantphone        :' => 'owner.phone',
            '2k. registrantfax          :' => 'owner.fax',
            '2l. registrantemail        :' => 'owner.email',
            '3e. creationdate           :' => 'domain.created',
            '4a. admin                  :' => 'admin.name',
            '4c. admincompany           :' => 'admin.organization',
            '4d. adminpostaladdr        :' => 'admin.address',
            '4e. adminphone             :' => 'admin.phone',
            '4f. adminfax               :' => 'admin.fax',
            '4g. adminemail             :' => 'admin.email',
            '5a. tec                    :' => 'tech.name',
            '5c. teccompany             :' => 'tech.organization',
            '5d. tecpostaladdr          :' => 'tech.address',
            '5e. tecphone               :' => 'tech.phone',
            '5f. tecfax                 :' => 'tech.fax',
            '5g. tecemail               :' => 'tech.email',
            '6a. primnsfqdn             :' => 'domain.nserver.0',
            '6e. secns1fqdn             :' => 'domain.nserver.1',
            '6i. secns2fqdn             :' => 'domain.nserver.2',
            '6m. secns3fqdn             :' => 'domain.nserver.3',
            '6q. secns4fqdn             :' => 'domain.nserver.4'
        );

        $r = [
            'rawdata' => $data_str['rawdata'],
        ];

        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items);

        $r['regyinfo']['referrer'] = 'https://www.co.za';
        $r['regyinfo']['registrar'] = 'UniForum Association';

        return $r;
    }
}
