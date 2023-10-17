<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class LyHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'owner' => 'Registrant:',
            'admin' => 'Administrative Contact:',
            'tech' => 'Technical Contact:',
            'domain.name' => 'Domain Name:',
            'domain.status' => 'Domain Status:',
            'domain.created' => 'Created:',
            'domain.changed' => 'Updated:',
            'domain.expires' => 'Expired:',
            'domain.nserver' => 'Domain servers in listed order:'
        ];

        $extra = ['zip/postal code:' => 'address.pcode'];

        $r = [
            'regrinfo' => static::getBlocks($data_str['rawdata'], $items),
            'regyinfo' => $this->parseRegistryInfo($data_str['rawdata']) ?? [
                'referrer' => 'https://www.nic.ly',
                'registrar' => 'Libya ccTLD'
            ],
        ];

        if (!empty($r['regrinfo']['domain']['name'])) {
            $r['regrinfo'] = static::getContacts($r['regrinfo'], $extra);
            $r['regrinfo']['domain']['name'] = $r['regrinfo']['domain']['name'][0];
            $r['regrinfo']['registered'] = 'yes';
        } else {
            $r = ['regrinfo' => []];
            $r['regrinfo']['registered'] = 'no';
        }

        return $r;
    }
}
