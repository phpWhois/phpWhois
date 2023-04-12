<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class SiHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $translate = [
            'nic-hdl' => 'handle',
            'nameserver' => 'nserver'
        ];

        $contacts = [
            'registrant' => 'owner',
            'tech-c' => 'tech'
        ];

        $r = [];
        $r['regrinfo'] = static::generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');
        $r['regyinfo'] = [
            'referrer' => 'https://www.arnes.si',
            'registrar' => 'ARNES'
        ];

        return $r;
    }
}
