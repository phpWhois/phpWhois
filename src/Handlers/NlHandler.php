<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class NlHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'domain.name' => 'Domain name:',
            'domain.status' => 'Status:',
            'domain.nserver' => 'Domain nameservers:',
            'domain.created' => 'Date registered:',
            'domain.changed' => 'Record last updated:',
            'domain.sponsor' => 'Registrar:',
            'admin' => 'Administrative contact:',
            'tech' => 'Technical contact(s):'
        ];

        $r = [
            'regrinfo' => static::getBlocks($data_str['rawdata'], $items),
            'regyinfo' => $this->parseRegistryInfo($data_str['rawdata']) ?? [
                'referrer'  => 'https://www.domain-registry.nl',
                'registrar' => 'Stichting Internet Domeinregistratie NL',
            ],
            'rawdata' => $data_str['rawdata'],
        ];

        if (!isset($r['regrinfo']['domain']['status'])) {
            $r['regrinfo']['registered'] = 'no';
            return $r;
        }

        if (isset($r['regrinfo']['tech'])) {
            $r['regrinfo']['tech'] = static::getContact($r['regrinfo']['tech']);
        }

        if (isset($r['regrinfo']['zone'])) {
            $r['regrinfo']['zone'] = static::getContact($r['regrinfo']['zone']);
        }

        if (isset($r['regrinfo']['admin'])) {
            $r['regrinfo']['admin'] = static::getContact($r['regrinfo']['admin']);
        }

        if (isset($r['regrinfo']['owner'])) {
            $r['regrinfo']['owner'] = static::getContact($r['regrinfo']['owner']);
        }

        $r['regrinfo']['registered'] = 'yes';

        static::formatDates($r, 'dmy');

        return $r;
    }

    public static function getContact($array, $extra_items = array(), $has_org = false): array
    {
        $r = parent::getContact($array,$extra_items,$has_org);

        if (isset($r['name']) && preg_match('/^[A-Z0-9]+-[A-Z0-9]+$/', $r['name'])) {
            $r['handle'] = $r['name'];
            $r['name'] = array_shift($r['address']);
        }

        return $r;
    }
}
