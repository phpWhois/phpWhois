<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class OrgHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $r = [
            'regrinfo' => static::generic_parser_b($data_str['rawdata']),
            'regyinfo' => $this->parseRegistryInfo($data_str['rawdata']) ?? [
                'referrer'  => 'https://thenew.org/org-people/',
                'registrar' => 'Public Interest Registry',
            ],
            'rawdata'  => $data_str['rawdata'] ?? null,
        ];

        if (!strncmp($data_str['rawdata'][0], 'WHOIS LIMIT EXCEEDED', 20)) {
            $r['regrinfo']['registered'] = 'unknown';
        }

        return $r;
    }
}
