<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class IsHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $translate = array(
            'fax-no' => 'fax',
            'e-mail' => 'email',
            'nic-hdl' => 'handle',
            'person' => 'name'
        );

        $contacts = array(
            'owner-c' => 'owner',
            'admin-c' => 'admin',
            'tech-c' => 'tech',
            'billing-c' => 'billing',
            'zone-c' => 'zone'
        );

        $reg = static::generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'mdy');

        if (isset($reg['domain']['descr'])) {
            $reg['owner']['name'] = array_shift($reg['domain']['descr']);
            $reg['owner']['address'] = $reg['domain']['descr'];
            unset($reg['domain']['descr']);
        }

        $r = array();
        $r['regrinfo'] = $reg;
        $r['regyinfo'] = array(
            'referrer' => 'https://www.isnic.is',
            'registrar' => 'ISNIC'
        );
        return $r;
    }
}
