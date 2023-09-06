<?php
/** @noinspection PhpIllegalPsrClassPathInspection */

namespace phpWhois\Handlers;


class FrHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $translate = [
            'fax-no' => 'fax',
            'e-mail' => 'email',
            'nic-hdl' => 'handle',
            'ns-list' => 'handle',
            'person' => 'name',
            'address' => 'address.',
            'descr' => 'desc',
            'anniversary' => '',
            'domain' => 'name',
            'last-update' => 'changed',
            'registered' => 'created',
            'Expiry Date' => 'expires',
            'country' => 'address.country',
            'registrar' => 'sponsor',
            'role' => 'organization',
        ];

        $contacts = [
            'admin-c' => 'admin',
            'tech-c' => 'tech',
            'zone-c' => 'zone',
            'holder-c' => 'owner',
            'nsl-id' => 'nserver',
        ];

        $reg = static::generic_parser_a($data_str['rawdata'], $translate, $contacts, 'domain', 'dmY');

        if (isset($reg['nserver'])) {
            $reg['domain'] = array_merge($reg['domain'], $reg['nserver']);
            unset($reg['nserver']);
        }

        return [
            'regrinfo' => $reg,
            'regyinfo' => $this->parseRegistryInfo($data_str['rawdata']) ?? [
                'referrer' => 'https://www.nic.fr',
                'registrar' => 'AFNIC',
            ],
            'rawdata' => $data_str['rawdata'],
        ];
    }

    public static function generic_parser_a_blocks(array $rawdata, array $translate, array &$disclaimer=[]): array
    {
        $blocks = parent::generic_parser_a_blocks($rawdata, $translate, $disclaimer);

        array_walk_recursive($blocks, static function (&$v, $key){
            if (!in_array($key, ['expires', 'created', 'changed'])) {
                return;
            }

            $matches = [];
            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z/';
            if (preg_match($pattern, $v, $matches)) {
                $v = $matches[0];
            }
        });

        return $blocks;
    }
}
