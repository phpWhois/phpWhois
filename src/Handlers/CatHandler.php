<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class CatHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $r = [
            'regrinfo' => static::generic_parser_b($data_str['rawdata']),
            'regyinfo' => [
                'registrar' => 'Domini punt CAT',
                'referrer' => 'https://domini.cat/'
            ],
            'rawdata' => $data_str['rawdata'],
        ];

        if (!isset($r['regrinfo']['domain']['name'])) {
            $r['regrinfo']['registered'] = 'no';
        }

        return $r;
    }
}
