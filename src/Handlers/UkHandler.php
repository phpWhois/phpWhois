<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

class UkHandler extends AbstractHandler
{
    const ITEMS = [
        'owner.organization' => 'Registrant:',
        'owner.address'      => "Registrant's address:",
        'owner.type'         => 'Registrant type:',
        'domain.created'     => 'Registered on:',
        'domain.changed'     => 'Last updated:',
        'domain.expires'     => 'Expiry date:',
        'domain.nserver'     => 'Name servers:',
        'domain.sponsor'     => 'Registrar:',
        'domain.status'      => 'Registration status:',
        'domain.dnssec'      => 'DNSSEC:',
        ''                   => 'WHOIS lookup made at',
        'disclaimer'         => '--',
    ];

    /**
     * @param array  $data_str
     * @param string $query
     *
     * @return array
     */
    public function parse(array $data_str, string $query): array
    {
        $rawData = $this->removeBlankLines($data_str['rawdata']);
        $r       = [
            'regrinfo' => $this->get_blocks($rawData, static::ITEMS),
        ];

        if (isset($r['regrinfo']['owner'])) {
            $r['regrinfo']['owner']['organization'] = $r['regrinfo']['owner']['organization'][0];
            $r['regrinfo']['domain']['sponsor']     = $r['regrinfo']['domain']['sponsor'][0];
            $r['regrinfo']['registered']            = 'yes';
        } elseif (strpos($query, '.co.uk') && isset($r['regrinfo']['domain']['status'][0])) {
            if ($r['regrinfo']['domain']['status'][0] == 'Registered until expiry date.') {
                $r['regrinfo']['registered'] = 'yes';
            }
        } else {
            if (strpos($data_str['rawdata'][1], 'Error for ')) {
                $r['regrinfo']['registered']       = 'yes';
                $r['regrinfo']['domain']['status'] = 'invalid';
            } else {
                $r['regrinfo']['registered'] = 'no';
            }
        }

        $r = $this->format_dates($r, 'dmy');

        $r['regyinfo'] = [
            'referrer'  => 'http://www.nominet.org.uk',
            'registrar' => 'Nominet UK',
        ];

        if (!array_key_exists('rawdata', $r) && array_key_exists('rawdata', $data_str)) {
            $r['rawdata'] = $data_str['rawdata'];
        }

        return $r;
    }
}
