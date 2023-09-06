<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class IrHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $translate = [
            'nic-hdl' => 'handle',
            'org' => 'organization',
            'e-mail' => 'email',
            'person' => 'name',
            'fax-no' => 'fax',
            'domain' => 'name'
        ];

        $contacts = [
            'admin-c' => 'admin',
            'tech-c' => 'tech',
            'holder-c' => 'owner'
        ];

        return [
            'regrinfo' => static::generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd'),
            'regyinfo' => $this->parseRegistryInfo($data_str['rawdata']) ?? [
                'referrer' => 'http://whois.nic.ir/',
                'registrar' => 'NIC-IR'
            ],
            'rawdata' => $data_str['rawdata'],
        ];
    }
}
