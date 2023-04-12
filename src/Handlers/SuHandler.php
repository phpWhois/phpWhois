<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class SuHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = array(
            'domain:' => 'domain.name',
            'registrar:' => 'domain.sponsor',
            'state:' => 'domain.status',
            'person:' => 'owner.name',
            'phone:' => 'owner.phone',
            'e-mail:' => 'owner.email',
            'created:' => 'domain.created',
            'paid-till:' => 'domain.expires',
                /*
                  'nserver:' => 'domain.nserver.',
                  'source:' => 'domain.source',
                  'type:' => 'owner.type',
                  'org:' => 'owner.organization',
                  'fax-no:' => 'owner.fax',
                 */
        );

        $r = array();
        $r['regrinfo'] = static::generic_parser_b($data_str['rawdata'], $items, 'dmy');

        $r['regyinfo'] = array(
            'referrer' => 'https://www.ripn.net',
            'registrar' => 'RUCENTER-REG-RIPN'
        );
        return $r;
    }
}
