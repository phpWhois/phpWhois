<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class MxHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'owner' => 'Registrant:',
            'admin' => 'Administrative Contact:',
            'tech' => 'Technical Contact:',
            'billing' => 'Billing Contact:',
            'domain.nserver' => 'Name Servers:',
            'domain.created' => 'Created On:',
            'domain.expires' => 'Expiration Date:',
            'domain.changed' => 'Last Updated On:',
            'domain.sponsor' => 'Registrar:'
        ];

        $extra = [
            'city:' => 'address.city',
            'state:' => 'address.state',
            'dns:' => '0'
        ];

        $r = [];
        $r['regrinfo'] = static::easyParser($data_str['rawdata'], $items, 'dmy', $extra);

        $r['regyinfo'] = [
            'registrar' => 'NIC Mexico',
            'referrer' => 'https://www.nic.mx/'
        ];

        if (empty($r['regrinfo']['domain']['created'])) {
            $r['regrinfo']['registered'] = 'no';
        } else {
            $r['regrinfo']['registered'] = 'yes';
        }

        return $r;
    }
}
