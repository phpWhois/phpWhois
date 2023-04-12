<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class ScHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $r = array();
        $r['regrinfo'] = static::generic_parser_b($data_str['rawdata'], array(), 'dmy');
        $r['regyinfo'] = array(
            'referrer' => 'http://www.nic.sc',
            'registrar' => 'VCS (Pty) Limited'
        );
        return $r;
    }
}
