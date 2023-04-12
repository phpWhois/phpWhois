<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class CoHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $r = [
            'rawdata' => $data_str['rawdata'],
        ];

        $r['regrinfo']              = generic_parser_b($data_str['rawdata'], [], 'mdy');
        $r['regyinfo']['referrer']  = 'http://www.cointernet.com.co/';
        $r['regyinfo']['registrar'] = '.CO Internet, S.A.S.';

        return $r;
    }
}
