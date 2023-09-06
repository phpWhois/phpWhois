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

        return [
            'regrinfo' => static::generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd'),
            'regyinfo' => $this->parseRegistryInfo($data_str['rawdata']) ?? [
                'referrer' => 'https://www.arnes.si',
                'registrar' => 'ARNES'
            ],
            'rawdata'  => $data_str['rawdata'],
        ];
    }
}
