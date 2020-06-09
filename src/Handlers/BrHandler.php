<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

class BrHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $translate = [
            'fax-no'     => 'fax',
            'e-mail'     => 'email',
            'nic-hdl-br' => 'handle',
            'person'     => 'name',
            'netname'    => 'name',
            'domain'     => 'name',
            'updated'    => '',
        ];

        $contacts = [
            'owner-c'   => 'owner',
            'tech-c'    => 'tech',
            'admin-c'   => 'admin',
            'billing-c' => 'billing',
        ];

        $r = $this->generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');

        if (in_array('Permission denied.', $r['disclaimer'])) {
            $r['registered'] = 'unknown';

            return $r;
        }

        if (isset($r['domain']['nsstat'])) {
            unset($r['domain']['nsstat']);
        }
        if (isset($r['domain']['nslastaa'])) {
            unset($r['domain']['nslastaa']);
        }

        if (isset($r['domain']['owner'])) {
            $r['owner']['organization'] = $r['domain']['owner'];
            unset($r['domain']['owner']);
        }

        if (isset($r['domain']['responsible'])) {
            unset($r['domain']['responsible']);
        }
        if (isset($r['domain']['address'])) {
            unset($r['domain']['address']);
        }
        if (isset($r['domain']['phone'])) {
            unset($r['domain']['phone']);
        }

        $a = [
            'regrinfo' => $r,
            'regyinfo' => [
                'registrar' => 'BR-NIC',
                'referrer'  => 'http://www.nic.br',
            ],
            'rawdata'  => $data_str['rawdata'],
        ];

        return $a;
    }
}
