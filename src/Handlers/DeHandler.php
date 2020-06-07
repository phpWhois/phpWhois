<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

class DeHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'domain.name'      => 'Domain:',
            'domain.nserver.'  => 'Nserver:',
            'domain.nserver.#' => 'Nsentry:',
            'domain.status'    => 'Status:',
            'domain.changed'   => 'Changed:',
            'domain.desc.'     => 'Descr:',
            'owner'            => '[Holder]',
            'admin'            => '[Admin-C]',
            'tech'             => '[Tech-C]',
            'zone'             => '[Zone-C]',
        ];

        $extra = [
            'city:'        => 'address.city',
            'postalcode:'  => 'address.pcode',
            'countrycode:' => 'address.country',
            'remarks:'     => '',
            'sip:'         => 'sip',
            'type:'        => '',
        ];

        $rawData = $this->removeBlankLines($data_str['rawdata']);
        $r       = [
            'rawdata'  => $data_str['rawdata'],
            'regrinfo' => $this->easy_parser($rawData, $items, 'ymd', $extra),
            'regyinfo' => [
                'registrar' => 'DENIC eG',
                'referrer'  => 'http://www.denic.de/',
            ],
        ];

        if (!isset($r['regrinfo']['domain']['status']) || $r['regrinfo']['domain']['status'] === 'free') {
            $r['regrinfo']['registered'] = 'no';
        } else {
            if (isset($r['regrinfo']['domain']['changed'])) {
                $r['regrinfo']['domain']['changed'] = substr($r['regrinfo']['domain']['changed'], 0, 10);
            }
            $r['regrinfo']['registered'] = 'yes';
        }

        return $r;
    }
}
