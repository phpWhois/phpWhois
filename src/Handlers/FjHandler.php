<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class FjHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'owner'          => 'Registrant:',
            'domain.name'    => 'Domain name:',
            'domain.status'  => 'Status:',
            'domain.expires' => 'Expires:',
            'domain.nserver' => 'Domain servers:',
        ];

        $r = [
            'rawdata' => $data_str['rawdata'],
        ];

        $r['regrinfo'] = static::getBlocks($data_str['rawdata'], $items);

        if (!empty($r['regrinfo']['domain']['status'])) {
            $r['regrinfo'] = static::getContacts($r['regrinfo']);

            date_default_timezone_set("Pacific/Fiji");

            if (isset($r['regrinfo']['domain']['expires'])) {
                $r['regrinfo']['domain']['expires'] = strftime(
                    "%Y-%m-%d",
                    strtotime($r['regrinfo']['domain']['expires'])
                );
            }

            $r['regrinfo']['registered'] = 'yes';
        } else {
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo'] = [
            'referrer'  => 'http://www.domains.fj',
            'registrar' => 'FJ Domain Name Registry',
        ];

        return $r;
    }
}
