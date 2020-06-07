<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

class ClHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'admin'          => '(Administrative Contact)',
            'tech'           => 'Contacto Técnico (Technical Contact):',
            // 'domain.nserver' => 'Servidores de nombre (Domain servers):',
            'domain.nserver' => 'Name server:',
            'domain.changed' => '(Database last updated on):',
            'domain.created' => 'Creation date:',
            'domain.expires' => 'Expiration date:',
        ];

        $trans = [
            'organización:' => 'organization',
            'nombre      :' => 'name',
        ];

        $rawData = $this->removeBlankLines($data_str['rawdata']);
        $r       = [
            'rawdata'  => $data_str['rawdata'],
            'regrinfo' => $this->easy_parser($rawData, $items, 'd-m-y', $trans),
        ];

        if (!isset($r['regrinfo']['domain']['name'])) {
            $r['regrinfo']['domain']['name'] = $query;
        }

        $r['regyinfo'] = [
            'referrer'  => 'http://www.nic.cl',
            'registrar' => 'NIC Chile',
        ];

        return $r;
    }
}
