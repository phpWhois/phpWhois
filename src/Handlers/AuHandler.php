<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

class AuHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'Domain Name:'              => 'domain.name',
            'Last Modified:'            => 'domain.changed',
            'Registrar Name:'           => 'domain.sponsor',
            'Status:'                   => 'domain.status',
            'Domain ROID:'              => 'domain.handle',
            'Registrant:'               => 'owner.organization',
            'Registrant Contact ID:'    => 'owner.handle',
            'Registrant Contact Email:' => 'owner.email',
            'Registrant Contact Name:'  => 'owner.name',
            'Tech Contact Name:'        => 'tech.name',
            'Tech Contact Email:'       => 'tech.email',
            'Tech Contact ID:'          => 'tech.handle',
            'Name Server:'              => 'domain.nserver.',
        ];

        $r = [
            'regrinfo' => $this->generic_parser_b($data_str['rawdata'], $items),
            'regyinfo' => [
                'referrer'  => 'http://www.aunic.net',
                'registrar' => 'AU-NIC',
            ],
            'rawdata'  => $data_str['rawdata'],
        ];

        return $r;
    }
}
