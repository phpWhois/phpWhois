<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class PlHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'domain.created' => 'created:',
            'domain.changed' => 'last modified:',
            'domain.sponsor' => 'REGISTRAR:',
            '#' => 'WHOIS displays data with a delay not exceeding 15 minutes in relation to the .pl Registry system'
        ];

        return [
            'regrinfo' => static::easyParser($data_str['rawdata'], $items, 'ymd'),
            'regyinfo' => $this->parseRegistryInfo($data_str['rawdata']) ?? [
                'referrer' => 'https://www.dns.pl/english/index.html',
                'registrar' => 'NASK'
            ],
            'rawdata'  => $data_str['rawdata'],
        ];
    }
}
