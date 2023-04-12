<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class CzHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $translate = [
            'expire'     => 'expires',
            'registered' => 'created',
            'nserver'    => 'nserver',
            'domain'     => 'name',
            'contact'    => 'handle',
            'reg-c'      => '',
            'descr'      => 'desc',
            'e-mail'     => 'email',
            'person'     => 'name',
            'org'        => 'organization',
            'fax-no'     => 'fax',
        ];

        $contacts = [
            'admin-c'    => 'admin',
            'tech-c'     => 'tech',
            'bill-c'     => 'billing',
            'registrant' => 'owner',
        ];

        $r             = [
            'rawdata' => $data_str['rawdata'],
        ];

        $r['regrinfo'] = static::generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'dmy');

        $r['regyinfo'] = [
            'referrer'  => 'http://www.nic.cz',
            'registrar' => 'CZ-NIC',
        ];

        if ($data_str['rawdata'][0] === 'Your connection limit exceeded. Please slow down and try again later.') {
            $r['regrinfo']['registered'] = 'unknown';
        }

        return $r;
    }
}
