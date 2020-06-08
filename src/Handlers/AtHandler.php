<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

class AtHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $translate = [
            'fax-no'         => 'fax',
            'e-mail'         => 'email',
            'nic-hdl'        => 'handle',
            'person'         => 'name',
            'personname'     => 'name',
            'street address' => 'address.street',
            'city'           => 'address.city',
            'postal code'    => 'address.pcode',
            'country'        => 'address.country',
            // 'domain'         => 'domain.name',
        ];

        $contacts = [
            'registrant' => 'owner',
            'admin-c'    => 'admin',
            'tech-c'     => 'tech',
            'billing-c'  => 'billing',
            'zone-c'     => 'zone',
        ];

        $reg = $this->generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');

        if (isset($reg['domain']['remarks'])) {
            unset($reg['domain']['remarks']);
        }

        if (isset($reg['domain']['descr'])) {
            foreach ($reg['domain']['descr'] as $key => $val) {
                $v = trim(substr(strstr($val, ':'), 1));
                if (strstr($val, '[organization]:')) {
                    $reg['owner']['organization'] = $v;
                    continue;
                }
                if (strstr($val, '[phone]:')) {
                    $reg['owner']['phone'] = $v;
                    continue;
                }
                if (strstr($val, '[fax-no]:')) {
                    $reg['owner']['fax'] = $v;
                    continue;
                }
                if (strstr($val, '[e-mail]:')) {
                    $reg['owner']['email'] = $v;
                    continue;
                }

                $reg['owner']['address'][$key] = $v;
            }

            unset($reg['domain']['descr']);
        }

        $r = [
            'regrinfo' => $reg,
            'regyinfo' => [
                'referrer'  => 'http://www.nic.at',
                'registrar' => 'NIC-AT',
            ],
            'rawdata'  => $data_str['rawdata'],
        ];

        return $r;
    }
}
