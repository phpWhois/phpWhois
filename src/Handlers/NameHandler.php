<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class NameHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        return [
            'regrinfo' => static::generic_parser_b($data_str['rawdata']),
            'regyinfo' => [
                'referrer' => 'https://www.nic.name/',
                'registrar' => 'Global Name Registry'
            ]
        ];
    }
}
