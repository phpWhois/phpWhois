<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;

use iana_handler;


class IntHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $iana = new iana_handler();
        $r = array();
        $r['regrinfo'] = $iana->parse($data_str['rawdata'], $query);
        $r['regyinfo']['referrer'] = 'http://www.iana.org/int-dom/int.htm';
        $r['regyinfo']['registrar'] = 'Internet Assigned Numbers Authority';
        return $r;
    }
}
