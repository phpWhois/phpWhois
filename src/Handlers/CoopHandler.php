<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class CoopHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'owner'           => 'Contact Type:            registrant',
            'admin'           => 'Contact Type:            admin',
            'tech'            => 'Contact Type:            tech',
            'billing'         => 'Contact Type:            billing',
            'domain.name'     => 'Domain Name:',
            'domain.handle'   => 'Domain ID:',
            'domain.expires'  => 'Expiry Date:',
            'domain.created'  => 'Creation Date:',
            'domain.changed'  => 'Updated Date:',
            'domain.status'   => 'Domain Status:',
            'domain.sponsor'  => 'Sponsoring registrar:',
            'domain.nserver.' => 'Host Name:',
        ];

        $translate = [
            'Contact ID:'     => 'handle',
            'Name:'           => 'name',
            'Organisation:'   => 'organization',
            'Street 1:'       => 'address.street.0',
            'Street 2:'       => 'address.street.1',
            'Street 3:'       => 'address.street.2',
            'City:'           => 'address.city',
            'State/Province:' => 'address.state',
            'Postal code:'    => 'address.pcode',
            'Country:'        => 'address.country',
            'Voice:'          => 'phone',
            'Fax:'            => 'fax',
            'Email:'          => 'email',
        ];

        $blocks = get_blocks($data_str['rawdata'], $items);

        $r = [
            'rawdata' => $data_str['rawdata'],
        ];

        if (isset($blocks['domain'])) {
            $r['regrinfo']['domain']     = AbstractHandler::formatDates($blocks['domain'], 'dmy');
            $r['regrinfo']['registered'] = 'yes';

            if (isset($blocks['owner'])) {
                $r['regrinfo']['owner'] = AbstractHandler::generic_parser_b($blocks['owner'], $translate, 'dmy', false);

                if (isset($blocks['tech'])) {
                    $r['regrinfo']['tech'] = AbstractHandler::generic_parser_b($blocks['tech'], $translate, 'dmy', false);
                }

                if (isset($blocks['admin'])) {
                    $r['regrinfo']['admin'] = AbstractHandler::generic_parser_b($blocks['admin'], $translate, 'dmy', false);
                }

                if (isset($blocks['billing'])) {
                    $r['regrinfo']['billing'] = AbstractHandler::generic_parser_b($blocks['billing'], $translate, 'dmy', false);
                }
            } else {
                $r['regrinfo']['owner'] = AbstractHandler::generic_parser_b($data_str['rawdata'], $translate, 'dmy', false);
            }
        } else {
            $r['regrinfo']['registered'] = 'no';
        }

        $r['regyinfo'] = [
            'referrer'  => 'https://www.nic.coop',
            'registrar' => '.coop registry',
        ];

        return $r;
    }
}
