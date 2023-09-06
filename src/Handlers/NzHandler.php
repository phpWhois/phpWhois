<?php
/** @noinspection PhpIllegalPsrClassPathInspection */

namespace phpWhois\Handlers;


class NzHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $items = array(
            'domain_name:' => 'domain.name',
            'query_status:' => 'domain.status',
            'ns_name_01:' => 'domain.nserver.0',
            'ns_name_02:' => 'domain.nserver.1',
            'ns_name_03:' => 'domain.nserver.2',
            'domain_dateregistered:' => 'domain.created',
            'domain_datelastmodified:' => 'domain.changed',
            'domain_datebilleduntil:' => 'domain.expires',
            'registrar_name:' => 'domain.sponsor',
            'registrant_contact_name:' => 'owner.name',
            'registrant_contact_address1:' => 'owner.address.address.0',
            'registrant_contact_address2:' => 'owner.address.address.1',
            'registrant_contact_address3:' => 'owner.address.address.2',
            'registrant_contact_postalcode:' => 'owner.address.pcode',
            'registrant_contact_city:' => 'owner.address.city',
            'Registrant State/Province:' => 'owner.address.state',
            'registrant_contact_country:' => 'owner.address.country',
            'registrant_contact_phone:' => 'owner.phone',
            'registrant_contact_fax:' => 'owner.fax',
            'registrant_contact_email:' => 'owner.email',
            'admin_contact_name:' => 'admin.name',
            'admin_contact_address1:' => 'admin.address.address.0',
            'admin_contact_address2:' => 'admin.address.address.1',
            'admin_contact_address3:' => 'admin.address.address.2',
            'admin_contact_postalcode:' => 'admin.address.pcode',
            'admin_contact_city:' => 'admin.address.city',
            'admin_contact_country:' => 'admin.address.country',
            'admin_contact_phone:' => 'admin.phone',
            'admin_contact_fax:' => 'admin.fax',
            'admin_contact_email:' => 'admin.email',
            'technical_contact_name:' => 'tech.name',
            'technical_contact_address0:' => 'tech.address.address.0',
            'technical_contact_address1:' => 'tech.address.address.1',
            'technical_contact_address2:' => 'tech.address.address.2',
            'technical_contact_postalcode:' => 'tech.address.pcode',
            'technical_contact_city:' => 'tech.address.city',
            'technical_contact_country:' => 'tech.address.country',
            'technical_contact_phone:' => 'tech.phone',
            'technical_contact_fax:' => 'tech.fax',
            'technical_contact_email:' => 'tech.email'
        );

        $r = [];
        $r['regrinfo'] = static::generic_parser_b($data_str['rawdata'], $items);

        $domain_status = '';
        if (!empty($r['regrinfo']['domain']['status'])){
            $domain_status = substr( $r['regrinfo']['domain']['status'], 0, 3 );
        }

        if ($domain_status === '200'){
            $r['regrinfo']['registered']='yes';
        }elseif ($domain_status === '220'){
            $r['regrinfo']['registered']='no';
        }else{
            $r['regrinfo']['registered']='unknown';
        }

        if (!strncmp($data_str['rawdata'][0], 'WHOIS LIMIT EXCEEDED', 20)){
            $r['regrinfo']['registered']='unknown';
        }

        $r['regyinfo'] = $this->parseRegistryInfo($data_str['rawdata']) ?? [
            'referrer' => 'https://www.dnc.org.nz',
            'registrar' => 'New Zealand Domain Name Registry Limited'
        ];

        return $r;
    }

    public static function generic_parser_a_blocks(array $rawdata, array $translate, array &$disclaimer=[]): array
    {
        $blocks = parent::generic_parser_a_blocks($rawdata, $translate, $disclaimer);

        array_walk_recursive($blocks, static function (&$v, $key){
            if (!in_array($key, ['expires', 'created', 'changed'])) {
                return;
            }

            $matches = [];
            $pattern = '/(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[-+])(\d{2}):(\d{2})/';
            preg_match($pattern, $v, $matches);

            if (!empty($matches)) {
                $v = $matches[1] . $matches[2] . $matches[3];
            }
        });

        return $blocks;
    }

}