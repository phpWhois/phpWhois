<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class ZanetHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = array(
            'domain.name' => 'Domain Name            : ',
            'domain.created' => 'Record Created         :',
            'domain.changed' => 'Record	Last Updated    :',
            'owner.name' => 'Registered for         :',
            'admin' => 'Administrative Contact :',
            'tech' => 'Technical Contact      :',
            'domain.nserver' => 'Domain Name Servers listed in order:',
            'registered' => 'No such domain: ',
            '' => 'The ZA NiC whois'
        );

        // Arrange contacts ...

        $rawdata = array();
        foreach ($data_str['rawdata'] as $line) {
            if (strpos($line, ' Contact ') !== false) {
                $pos = strpos($line, ':');

                if ($pos !== false) {
                    $rawdata[] = substr($line, 0, $pos + 1);
                    $rawdata[] = trim(substr($line, $pos + 1));
                    continue;
                }
            }
            $rawdata[] = $line;
        }

        $r = [
            'regrinfo' => static::getBlocks($rawdata, $items),
            'regyinfo' => $this->parseRegistryInfo($data_str['rawdata']) ?? [
                'referrer'  => 'https://www.za.net/',   // or http://www.za.org
                'registrar' => 'ZA NiC',
            ],
            'rawdata'  => $data_str['rawdata'],
        ];

        if (isset($r['regrinfo']['registered'])) {
            $r['regrinfo']['registered'] = 'no';
        } else {
            if (isset($r['regrinfo']['admin'])) {
                $r['regrinfo']['admin'] = static::getContact($r['regrinfo']['admin']);
            }

            if (isset($r['regrinfo']['tech'])) {
                $r['regrinfo']['tech'] = static::getContact($r['regrinfo']['tech']);
            }
        }

        static::formatDates($r, 'xmdxxy');

        return $r;
    }
}
