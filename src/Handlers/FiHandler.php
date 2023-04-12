<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class FiHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'domain.............:' => 'domain.name',
            'created............:' => 'domain.created',
            'modified...........:' => 'domain.changed',
            'expires............:' => 'domain.expires',
            'status.............:' => 'domain.status',
            'nserver............:' => 'domain.nserver.',
            'name...............:' => 'owner.name.',
            'address............:' => 'owner.address.',
            'country............:' => 'owner.country',
            'phone..............:' => 'owner.phone',
        ];

        $r = [
            'rawdata' => $data_str['rawdata'],
        ];

        $r['regrinfo'] = static::generic_parser_b($data_str['rawdata'], $items, 'dmy');

        $r['regyinfo'] = [
            'referrer'  => 'https://domain.ficora.fi/',
            'registrar' => 'Finnish Communications Regulatory Authority',
        ];

        return $r;
    }
}
