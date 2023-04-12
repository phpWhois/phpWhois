<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;

/**
 * @TODO BUG
 * - date on ro could be given as "mail date" (ex: updated field)
 * - multiple person for one role, ex: news.ro
 * - seems the only role listed is registrant
 */
class RoHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $translate = array(
            'fax-no' => 'fax',
            'e-mail' => 'email',
            'nic-hdl' => 'handle',
            'person' => 'name',
            'address' => 'address.',
            'domain-name' => '',
            'updated' => 'changed',
            'registration-date' => 'created',
            'domain-status' => 'status',
            'nameserver' => 'nserver'
        );

        $contacts = array(
            'admin-contact' => 'admin',
            'technical-contact' => 'tech',
            'zone-contact' => 'zone',
            'billing-contact' => 'billing'
        );

        $extra = array(
            'postal code:' => 'address.pcode'
        );

        $reg = static::generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'Ymd');

        if (isset($reg['domain']['description'])) {
            $reg['owner'] = get_contact($reg['domain']['description'], $extra);
            unset($reg['domain']['description']);

            foreach ($reg as $key => $item) {
                if (isset($item['address'])) {
                    $data = $item['address'];
                    unset($reg[$key]['address']);
                    $reg[$key] = array_merge($reg[$key], get_contact($data, $extra));
                }
            }

            $reg['registered'] = 'yes';
        } else {
            $reg['registered'] = 'no';
        }

        $r = array();
        $r['regrinfo'] = $reg;
        $r['regyinfo'] = array(
            'referrer' => 'https://www.nic.ro',
            'registrar' => 'nic.ro'
        );

        return $r;
    }
}
