<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

class AeroHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $r = [
            'regrinfo' => $this->generic_parser_b($data_str['rawdata'], [], 'ymd'),
            'regyinfo' => [
                'referrer'  => 'http://www.nic.aero',
                'registrar' => 'Societe Internationale de Telecommunications Aeronautiques SC',
            ],
            'rawdata'  => $data_str['rawdata'],
        ];

        return $r;
    }
}
