<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


/*
TODO:
   - whois - converter para http://domaininfo.com/idn_conversion.asp punnycode antes de efectuar a pesquisa
   - o punnycode deveria fazer parte dos resultados fazer parte dos resultados!
*/
class PtHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = [
            'domain.name'     => ' / Domain Name:',
            'domain.created'  => 'Data de registo / Creation Date (dd/mm/yyyy):',
            'domain.nserver.' => 'Nameserver:',
            'domain.status'   => 'Estado / Status:',
            'owner'           => 'Titular / Registrant',
            'billing'         => 'Entidade Gestora / Billing Contact',
            'admin'           => 'Responsável Administrativo / Admin Contact',
            'tech'            => 'Responsável Técnico / Tech Contact',
            '#'               => 'Nameserver Information',
        ];

        $r = [
            'regrinfo' => static::getBlocks($data_str['rawdata'], $items),
            'regyinfo' => $this->parseRegistryInfo($data_str['rawdata']) ?? [
                'registrar' => 'FCCN',
                'referrer' => 'https://www.fccn.pt'
            ],
            'rawdata' => $data_str['rawdata'],
        ];

        if (empty($r['regrinfo']['domain']['name'])) {
            print_r($r['regrinfo']);
            $r['regrinfo']['registered'] = 'no';
            return $r;
        }

        $r['regrinfo']['domain']['created'] = static::getDate($r['regrinfo']['domain']['created'], 'dmy');

        if ($r['regrinfo']['domain']['status'] === 'ACTIVE') {
            $r['regrinfo'] = static::getContacts($r['regrinfo']);
            $r['regrinfo']['registered'] = 'yes';
        } else {
            $r['regrinfo']['registered'] = 'no';
        }

        return $r;
    }
}
