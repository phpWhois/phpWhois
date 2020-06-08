<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

class AeHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'Domain Name:'                     => 'domain.name',
            'Registrar Name:'                  => 'domain.sponsor',
            'Status:'                          => 'domain.status',
            'Registrant Contact ID:'           => 'owner.handle',
            'Registrant Contact Name:'         => 'owner.name',
            'Registrant Contact Organisation:' => 'owner.organization',
            'Tech Contact Name:'               => 'tech.name',
            'Tech Contact ID:'                 => 'tech.handle',
            'Tech Contact Organisation:'       => 'tech.organization',
            'Name Server:'                     => 'domain.nserver.',
        ];

        $r = [
            'regrinfo' => $this->generic_parser_b($data_str['rawdata'], $items, 'ymd'),
            'regyinfo' => [
                'referrer'  => 'http://www.nic.ae',
                'registrar' => 'UAENIC',
            ],
            'rawdata'  => $data_str['rawdata'],
        ];

        return $r;
    }
}
