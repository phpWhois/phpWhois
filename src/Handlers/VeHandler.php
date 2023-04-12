<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 * @copyright Copyright (c) 2023 Kevin Lucich
 */

namespace phpWhois\Handlers;


class VeHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = array(
            'owner' => 'Titular:',
            'domain.name' => 'Nombre de Dominio:',
            'admin' => 'Contacto Administrativo',
            'tech' => 'Contacto Tecnico',
            'billing' => 'Contacto de Cobranza:',
            'domain.created' => 'Fecha de Creacion:',
            'domain.changed' => 'Ultima Actualizacion:',
            'domain.expires' => 'Fecha de Vencimiento:',
            'domain.status' => 'Estatus del dominio:',
            'domain.nserver' => 'Servidor(es) de Nombres de Dominio'
        );

        $r = array();
        $r['regrinfo'] = static::getBlocks($data_str['rawdata'], $items);

        if (!isset($r['regrinfo']['domain']['created']) || is_array($r['regrinfo']['domain']['created'])) {
            $r['regrinfo'] = array('registered' => 'no');
            return $r;
        }

        $dns = array();

        foreach ($r['regrinfo']['domain']['nserver'] as $nserv) {
            if ($nserv[0] === '-') {
                $dns[] = $nserv;
            }
        }

        $r['regrinfo']['domain']['nserver'] = $dns;
        $r['regrinfo'] = static::getContacts($r['regrinfo']);
        $r['regyinfo'] = array(
            'referrer' => 'https://registro.nic.ve',
            'registrar' => 'NIC-Venezuela - CNTI'
        );
        return $r;
    }
}
