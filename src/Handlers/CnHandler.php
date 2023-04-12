<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class CnHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'Domain Name:'                 => 'domain.name',
            'Domain Status:'               => 'domain.status.',
            'ROID:'                        => 'domain.handle',
            'Name Server:'                 => 'domain.nserver.',
            'Registration Date:'           => 'domain.created',
            'Registration Time:'           => 'domain.created',
            'Expiration Date:'             => 'domain.expires',
            'Expiration Time:'             => 'domain.expires',
            'Sponsoring Registrar:'        => 'domain.sponsor',
            'Registrant Name:'             => 'owner.name',
            'Registrant Organization:'     => 'owner.organization',
            'Registrant Address:'          => 'owner.address.address',
            'Registrant Postal Code:'      => 'owner.address.pcode',
            'Registrant City:'             => 'owner.address.city',
            'Registrant Country Code:'     => 'owner.address.country',
            'Registrant Email:'            => 'owner.email',
            'Registrant Phone Number:'     => 'owner.phone',
            'Registrant Fax:'              => 'owner.fax',
            'Administrative Name:'         => 'admin.name',
            'Administrative Organization:' => 'admin.organization',
            'Administrative Address:'      => 'admin.address.address',
            'Administrative Postal Code:'  => 'admin.address.pcode',
            'Administrative City:'         => 'admin.address.city',
            'Administrative Country Code:' => 'admin.address.country',
            'Administrative Email:'        => 'admin.email',
            'Administrative Phone Number:' => 'admin.phone',
            'Administrative Fax:'          => 'admin.fax',
            'Technical Name:'              => 'tech.name',
            'Technical Organization:'      => 'tech.organization',
            'Technical Address:'           => 'tech.address.address',
            'Technical Postal Code:'       => 'tech.address.pcode',
            'Technical City:'              => 'tech.address.city',
            'tec-country:'                 => 'tech.address.country',
            'Technical Email:'             => 'tech.email',
            'Technical Phone Number:'      => 'tech.phone',
            'Technical Fax:'               => 'tech.fax',
            'Billing Name:'                => 'billing.name',
            'Billing Organization:'        => 'billing.organization',
            'Billing Address:'             => 'billing.address.address',
            'Billing Postal Code:'         => 'billing.address.pcode',
            'Billing City:'                => 'billing.address.city',
            'Billing Country Code:'        => 'billing.address.country',
            'Billing Email:'               => 'billing.email',
            'Billing Phone Number:'        => 'billing.phone',
            'Billing Fax:'                 => 'billing.fax',
        ];

        return [
            'regrinfo' => static::generic_parser_b($data_str['rawdata'], $items, 'ymd'),
            'regyinfo' => [
                'referrer'  => 'http://www.cnnic.net.cn',
                'registrar' => 'China NIC',
            ],
            'rawdata' => $data_str['rawdata'],
        ];
    }
}
