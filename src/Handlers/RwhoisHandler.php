<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class RwhoisHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = array(
            'network:Organization-Name:' => 'owner.name',
            'network:Organization;I:' => 'owner.organization',
            'network:Organization-City:' => 'owner.address.city',
            'network:Organization-Zip:' => 'owner.address.pcode',
            'network:Organization-Country:' => 'owner.address.country',
            'network:IP-Network-Block:' => 'network.inetnum',
            'network:IP-Network:' => 'network.inetnum',
            'network:Network-Name:' => 'network.name',
            'network:ID:' => 'network.handle',
            'network:Created:' => 'network.created',
            'network:Updated:' => 'network.changed',
            'network:Tech-Contact;I:' => 'tech.email',
            'network:Admin-Contact;I:' => 'admin.email'
        );

        $res = static::generic_parser_b($data_str, $items, 'Ymd', false);

        if (isset($res['disclaimer'])) {
            unset($res['disclaimer']);
        }

        return [
            'regrinfo' => $res,
            'rawdata' => $data_str['rawdata'],
        ];
    }
}
