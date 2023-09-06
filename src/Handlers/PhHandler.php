<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class PhHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'created:' => 'domain.created',
            'changed:' => 'domain.changed',
            'status:' => 'domain.status',
            'nserver:' => 'domain.nserver.'
        ];

        $r = [
            'regrinfo' => static::generic_parser_b($data_str['rawdata'], $items),
            'regyinfo' => $this->parseRegistryInfo($data_str['rawdata']),
            'rawdata'  => $data_str['rawdata'],
        ];

        if (!isset($r['regrinfo']['domain']['name'])) {
            $r['regrinfo']['domain']['name'] = $query;
        }

        return $r;
    }
}
