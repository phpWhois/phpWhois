<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class NlHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = array(
            'domain.name' => 'Domain name:',
            'domain.status' => 'Status:',
            'domain.nserver' => 'Domain nameservers:',
            'domain.created' => 'Date registered:',
            'domain.changed' => 'Record last updated:',
            'domain.sponsor' => 'Registrar:',
            'admin' => 'Administrative contact:',
            'tech' => 'Technical contact(s):'
        );

        $r = array();
        $r['regrinfo'] = get_blocks($data['rawdata'], $items);
        $r['regyinfo']['referrer'] = 'http://www.domain-registry.nl';
        $r['regyinfo']['registrar'] = 'Stichting Internet Domeinregistratie NL';

        if (!isset($r['regrinfo']['domain']['status'])) {
            $r['regrinfo']['registered'] = 'no';
            return $r;
        }

        if (isset($r['regrinfo']['tech'])) {
            $r['regrinfo']['tech'] = $this->get_contact($r['regrinfo']['tech']);
        }

        if (isset($r['regrinfo']['zone'])) {
            $r['regrinfo']['zone'] = $this->get_contact($r['regrinfo']['zone']);
        }

        if (isset($r['regrinfo']['admin'])) {
            $r['regrinfo']['admin'] = $this->get_contact($r['regrinfo']['admin']);
        }

        if (isset($r['regrinfo']['owner'])) {
            $r['regrinfo']['owner'] = $this->get_contact($r['regrinfo']['owner']);
        }

        $r['regrinfo']['registered'] = 'yes';
        format_dates($r, 'dmy');
        return $r;
    }

    function get_contact($data)
    {
        $r = get_contact($data);

        if (isset($r['name']) && preg_match('/^[A-Z0-9]+-[A-Z0-9]+$/', $r['name'])) {
            $r['handle'] = $r['name'];
            $r['name'] = array_shift($r['address']);
        }

        return $r;
    }
}
