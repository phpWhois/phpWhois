<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

class BizHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $r = [
            'regrinfo' => $this->generic_parser_b($data_str['rawdata'], [], 'mdy'),
            'regyinfo' => [
                'referrer'  => 'http://www.neulevel.biz',
                'registrar' => 'NEULEVEL',
            ],
            'rawdata'  => $data_str['rawdata'],
        ];

        return $r;
    }
}
