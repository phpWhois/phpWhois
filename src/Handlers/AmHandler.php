<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

class AmHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'owner'          => 'Registrant:',
            'domain.name'    => 'Domain name:',
            'domain.created' => 'Registered:',
            'domain.changed' => 'Last modified:',
            'domain.nserver' => 'DNS servers:',
            'domain.status'  => 'Status:',
            'tech'           => 'Technical contact:',
            'admin'          => 'Administrative contact:',
        ];

        $rawData = $this->removeBlankLines($data_str['rawdata']);
        $r = [
            'regrinfo' => $this->get_blocks($rawData, $items),
            'rawdata' => $data_str['rawdata'],
        ];

        if (!empty($r['regrinfo']['domain']['name'])) {
            $r['regrinfo']               = $this->get_contacts($r['regrinfo']);
            $r['regrinfo']['registered'] = 'yes';
        } else {
            $r                           = [];
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo'] = [
            'referrer'  => 'http://www.isoc.am',
            'registrar' => 'ISOCAM',
        ];

        return $r;
    }

}
