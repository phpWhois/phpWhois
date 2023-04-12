<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class SeHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'domain:' => 'domain.name',
            'state:' => 'domain.status.',
            'status:' => 'domain.status.',
            'expires:' => 'domain.expires',
            'created:' => 'domain.created',
            'modified:' => 'domain.changed',
            'nserver:' => 'domain.nserver.',
            'registrar:' => 'domain.sponsor',
            'holder:' => 'owner.handle',
        ];

        $r = [];
        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'ymd', false);

        $r['regrinfo']['registered'] = isset($r['regrinfo']['domain']['name']) ? 'yes' : 'no';

        $r['regyinfo'] = [
            'referrer' => 'http://www.nic-se.se',
            'registrar' => 'NIC-SE'
        ];
        $r['rawdata'] = $data_str['rawdata'];
        return $r;
    }
}
