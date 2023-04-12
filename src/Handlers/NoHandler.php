<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class NoHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'Domain Name................:' => 'domain.name',
            'Created:'                     => 'domain.created',
            'Last updated:'                => 'domain.changed',
            'NORID Handle...............:' => 'domain.handle',
        ];

        $r = [
            'regrinfo' => generic_parser_b($data_str['rawdata'], $items, 'ymd', false),
            'regyinfo' => [
                'referrer'  => 'https://www.norid.no/en/',
                'registrar' => 'Norid',
            ],
            'rawdata'  => $data_str['rawdata'],
        ];

        $r['regrinfo']['registered'] = isset($r['regrinfo']['domain']['name']) ? 'yes' : 'no';

        return $r;
    }
}
