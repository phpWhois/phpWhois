<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class DkHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $translate = [
            'Name' => 'name',
            'Address' => 'address.street',
            'City' => 'address.city',
            'Postalcode' => 'address.pcode',
            'Country' => 'address.country'
        ];

        $disclaimer = [];
        $blocks = AbstractHandler::generic_parser_a_blocks($data_str['rawdata'], $translate, $disclaimer);

        $reg = [];
        if (isset($disclaimer) && is_array($disclaimer)) {
            $reg['disclaimer'] = $disclaimer;
        }

        if (empty($blocks) || !is_array($blocks['main'])) {
            $reg['registered'] = 'no';
        } else {
            $r = $blocks['main'];
            $reg['registered'] = 'yes';

            $ownerHandlePos = array_search('Registrant', $data_str['rawdata'], true) + 1;
            $ownerHandle = trim(substr(strstr($data_str['rawdata'][$ownerHandlePos], ':'), 1));

            $adminHandlePos = array_search('Administrator', $data_str['rawdata'], true) + 1;
            $adminHandle = trim(substr(strstr($data_str['rawdata'][$adminHandlePos], ':'), 1));

            $contacts = [
                'owner' => $ownerHandle,
                'admin' => $adminHandle,
            ];

            foreach ($contacts as $key => $val) {
                $blk = strtoupper(strtok($val, ' '));
                if (isset($blocks[$blk])) {
                    $reg[$key] = $blocks[$blk];
                }
            }

            $reg['domain'] = $r;

            static::formatDates($reg, 'Ymd');
        }

        return [
            'regrinfo' => $reg,
            'regyinfo' => [
                'referrer' => 'https://www.dk-hostmaster.dk/',
                'registrar' => 'DK Hostmaster'
            ],
            'rawdata' => $data_str['rawdata'],
        ];
    }
}
