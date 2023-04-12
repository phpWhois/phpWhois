<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;

use phpWhois\WhoisClient;


class JpHandler extends WhoisClient
{
    function parse($data_str, $query)
    {
        $items = array(
            '[State]' => 'domain.status',
            '[Status]' => 'domain.status',
            '[Registered Date]' => 'domain.created',
            '[Created on]' => 'domain.created',
            '[Expires on]' => 'domain.expires',
            '[Last Updated]' => 'domain.changed',
            '[Last Update]' => 'domain.changed',
            '[Organization]' => 'owner.organization',
            '[Name]' => 'owner.name',
            '[Email]' => 'owner.email',
            '[Postal code]' => 'owner.address.pcode',
            '[Postal Address]' => 'owner.address.street',
            '[Phone]' => 'owner.phone',
            '[Fax]' => 'owner.fax',
            '[Administrative Contact]' => 'admin.handle',
            '[Technical Contact]' => 'tech.handle',
            '[Name Server]' => 'domain.nserver.'
        );

        $r = array();
        $r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'ymd');

        $r['regyinfo'] = array(
            'referrer' => 'http://www.jprs.jp',
            'registrar' => 'Japan Registry Services'
        );

        if (!$this->deepWhois) {
            return $r;
        }

        $r['rawdata'] = $data_str['rawdata'];

        $items = array(
            'a. [JPNIC Handle]' => 'handle',
            'c. [Last, First]' => 'name',
            'd. [E-Mail]' => 'email',
            'g. [Organization]' => 'organization',
            'o. [TEL]' => 'phone',
            'p. [FAX]' => 'fax',
            '[Last Update]' => 'changed'
        );

        $this->query['server'] = 'jp.whois-servers.net';

        if (!empty($r['regrinfo']['admin']['handle'])) {
            $rwdata = $this->getRawData('CONTACT ' . $r['regrinfo']['admin']['handle'] . '/e');
            $r['rawdata'][] = '';
            $r['rawdata'] = array_merge($r['rawdata'], $rwdata);
            $r['regrinfo']['admin'] = generic_parser_b($rwdata, $items, 'ymd', false);
            $r = $this->setWhoisInfo($r);
        }

        if (!empty($r['regrinfo']['tech']['handle'])) {
            if (
                !empty($r['regrinfo']['admin']['handle']) &&
                    $r['regrinfo']['admin']['handle'] == $r['regrinfo']['tech']['handle']
            ) {
                $r['regrinfo']['tech'] = $r['regrinfo']['admin'];
            } else {
                unset($this->query);
                $this->query['server'] = 'jp.whois-servers.net';
                $rwdata = $this->getRawData('CONTACT ' . $r['regrinfo']['tech']['handle'] . '/e');
                $r['rawdata'][] = '';
                $r['rawdata'] = array_merge($r['rawdata'], $rwdata);
                $r['regrinfo']['tech'] = generic_parser_b($rwdata, $items, 'ymd', false);
                $r = $this->setWhoisInfo($r);
            }
        }

        return $r;
    }
}
