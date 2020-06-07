<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

class EduHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'domain.name'    => 'Domain name:',
            'domain.sponsor' => 'Registrar:',
            'domain.nserver' => 'Name Servers:',
            'domain.changed' => 'Domain record last updated:',
            'domain.created' => 'Domain record activated:',
            'domain.expires' => 'Domain expires:',
            'owner'          => 'Registrant:',
            'admin'          => 'Administrative Contact:',
            'tech'           => 'Technical Contact:',
            'billing'        => 'Billing Contact:',
        ];

        $rawData = $this->removeBlankLines($data_str['rawdata']);
        $r = [
            'rawdata'  => $data_str['rawdata'],
            'regrinfo' => $this->easy_parser($rawData, $items, 'dmy'),
        ];

        if (isset($b['tech'])) {
            if ($r['regrinfo']['tech']['name'] === 'Same as above') {
                $r['regrinfo']['tech'] = $r['regrinfo']['admin'];
            }
        }

        if (!isset($r['regrinfo']['domain']['name'])) {
            $r['regrinfo']['domain']['name'] = $query;
        }

        $r['regyinfo']['referrer']  = 'http://whois.educause.net';
        $r['regyinfo']['registrar'] = 'EDUCASE';

        return $r;
    }
}
