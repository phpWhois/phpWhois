<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class NuHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'name' => 'Domain Name (UTF-8):',
            'created' => 'Record created on',
            'expires' => 'Record expires on',
            'changed' => 'Record last updated on',
            'status' => 'Record status:',
            'handle' => 'Record ID:'
        ];

        $r = [
            'regrinfo' => [],
            'regyinfo' => $this->parseRegistryInfo($data_str['rawdata']) ?? [
                'whois' => 'whois.nic.nu',
                'referrer' => 'http://www.nunames.nu',
                'registrar' => '.NU Domain, Ltd'
            ],
            'rawdata'  => $data_str['rawdata'],
        ];

        foreach ($data_str['rawdata'] as $val) {
            $val = trim($val);

            if ($val !== '') {
                if ($val === 'Domain servers in listed order:') {
                    foreach ($data_str['rawdata'] as $val2) {
                        $val2 = trim($val2);
                        if ($val2 === '') {
                            break;
                        }
                        $r['regrinfo']['domain']['nserver'][] = $val2;
                    }
                    break;
                }

                foreach ($items as $field => $match) {
                    if ( strpos( $val, $match )!==false ) {
                        $r['regrinfo']['domain'][$field] = trim(substr($val, strlen($match)));
                        break;
                    }
                }
            }
        }

        if (isset($r['regrinfo']['domain'])) {
            $r['regrinfo']['registered'] = 'yes';
        } else {
            $r['regrinfo']['registered'] = 'no';
        }

        static::formatDates($r, 'dmy');

        return $r;
    }
}
