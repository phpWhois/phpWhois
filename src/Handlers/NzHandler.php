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

        $r = array();
        $r['regrinfo'] = $this->generic_parser_b($data_str['rawdata'], $items);

        if (!empty($r['regrinfo']['domain']['status']))
            $domain_status = substr($r['regrinfo']['domain']['status'], 0, 3);
        else
            $domain_status = '';

        if ($domain_status == '200')
            $r['regrinfo']['registered'] = 'yes';
        elseif ($domain_status == '220')
            $r['regrinfo']['registered'] = 'no';
        else
            $r['regrinfo']['registered'] = 'unknown';

        if (!strncmp($data_str['rawdata'][0], 'WHOIS LIMIT EXCEEDED', 20))
            $r['regrinfo']['registered'] = 'unknown';

        $r['regyinfo']['referrer'] = 'http://www.dnc.org.nz';
        $r['regyinfo']['registrar'] = 'New Zealand Domain Name Registry Limited';
        return $r;
    }

    function generic_parser_b($rawdata, $items = array(), $dateformat = 'mdy', $hasreg = true, $scanall = false) {
        if (is_array($items) && !count($items))
            $items = array(
                'Domain Name:' => 'domain.name',
                'Domain ID:' => 'domain.handle',
                'Sponsoring Registrar:' => 'domain.sponsor',
                'Registrar:' => 'domain.sponsor',
                'Registrar ID:' => 'domain.sponsor',
                'Domain Status:' => 'domain.status.',
                'Status:' => 'domain.status.',
                'Name Server:' => 'domain.nserver.',
                'Nameservers:' => 'domain.nserver.',
                'Maintainer:' => 'domain.referer',
                'Domain Registration Date:' => 'domain.created',
                'Domain Create Date:' => 'domain.created',
                'Domain Expiration Date:' => 'domain.expires',
                'Domain Last Updated Date:' => 'domain.changed',
                'Updated Date:' => 'domain.changed',
                'Creation Date:' => 'domain.created',
                'Last Modification Date:' => 'domain.changed',
                'Expiration Date:' => 'domain.expires',
                'Created On:' => 'domain.created',
                'Last Updated On:' => 'domain.changed',
                'Registry Expiry Date:' => 'domain.expires',
                'Registrant ID:' => 'owner.handle',
                'Registrant Name:' => 'owner.name',
                'Registrant Organization:' => 'owner.organization',
                'Registrant Address:' => 'owner.address.street.',
                'Registrant Address1:' => 'owner.address.street.',
                'Registrant Address2:' => 'owner.address.street.',
                'Registrant Street:' => 'owner.address.street.',
                'Registrant Street1:' => 'owner.address.street.',
                'Registrant Street2:' => 'owner.address.street.',
                'Registrant Street3:' => 'owner.address.street.',
                'Registrant Postal Code:' => 'owner.address.pcode',
                'Registrant City:' => 'owner.address.city',
                'Registrant State/Province:' => 'owner.address.state',
                'Registrant Country:' => 'owner.address.country',
                'Registrant Country/Economy:' => 'owner.address.country',
                'Registrant Phone Number:' => 'owner.phone',
                'Registrant Phone:' => 'owner.phone',
                'Registrant Facsimile Number:' => 'owner.fax',
                'Registrant FAX:' => 'owner.fax',
                'Registrant Email:' => 'owner.email',
                'Registrant E-mail:' => 'owner.email',
                'Administrative Contact ID:' => 'admin.handle',
                'Administrative Contact Name:' => 'admin.name',
                'Administrative Contact Organization:' => 'admin.organization',
                'Administrative Contact Address:' => 'admin.address.street.',
                'Administrative Contact Address1:' => 'admin.address.street.',
                'Administrative Contact Address2:' => 'admin.address.street.',
                'Administrative Contact Postal Code:' => 'admin.address.pcode',
                'Administrative Contact City:' => 'admin.address.city',
                'Administrative Contact State/Province:' => 'admin.address.state',
                'Administrative Contact Country:' => 'admin.address.country',
                'Administrative Contact Phone Number:' => 'admin.phone',
                'Administrative Contact Email:' => 'admin.email',
                'Administrative Contact Facsimile Number:' => 'admin.fax',
                'Administrative Contact Tel:' => 'admin.phone',
                'Administrative Contact Fax:' => 'admin.fax',
                'Administrative ID:' => 'admin.handle',
                'Administrative Name:' => 'admin.name',
                'Administrative Organization:' => 'admin.organization',
                'Administrative Address:' => 'admin.address.street.',
                'Administrative Address1:' => 'admin.address.street.',
                'Administrative Address2:' => 'admin.address.street.',
                'Administrative Postal Code:' => 'admin.address.pcode',
                'Administrative City:' => 'admin.address.city',
                'Administrative State/Province:' => 'admin.address.state',
                'Administrative Country/Economy:' => 'admin.address.country',
                'Administrative Phone:' => 'admin.phone',
                'Administrative E-mail:' => 'admin.email',
                'Administrative Facsimile Number:' => 'admin.fax',
                'Administrative Tel:' => 'admin.phone',
                'Administrative FAX:' => 'admin.fax',
                'Admin ID:' => 'admin.handle',
                'Admin Name:' => 'admin.name',
                'Admin Organization:' => 'admin.organization',
                'Admin Street:' => 'admin.address.street.',
                'Admin Street1:' => 'admin.address.street.',
                'Admin Street2:' => 'admin.address.street.',
                'Admin Street3:' => 'admin.address.street.',
                'Admin Address:' => 'admin.address.street.',
                'Admin Address2:' => 'admin.address.street.',
                'Admin Address3:' => 'admin.address.street.',
                'Admin City:' => 'admin.address.city',
                'Admin State/Province:' => 'admin.address.state',
                'Admin Postal Code:' => 'admin.address.pcode',
                'Admin Country:' => 'admin.address.country',
                'Admin Country/Economy:' => 'admin.address.country',
                'Admin Phone:' => 'admin.phone',
                'Admin FAX:' => 'admin.fax',
                'Admin Email:' => 'admin.email',
                'Admin E-mail:' => 'admin.email',
                'Technical Contact ID:' => 'tech.handle',
                'Technical Contact Name:' => 'tech.name',
                'Technical Contact Organization:' => 'tech.organization',
                'Technical Contact Address:' => 'tech.address.street.',
                'Technical Contact Address1:' => 'tech.address.street.',
                'Technical Contact Address2:' => 'tech.address.street.',
                'Technical Contact Postal Code:' => 'tech.address.pcode',
                'Technical Contact City:' => 'tech.address.city',
                'Technical Contact State/Province:' => 'tech.address.state',
                'Technical Contact Country:' => 'tech.address.country',
                'Technical Contact Phone Number:' => 'tech.phone',
                'Technical Contact Facsimile Number:' => 'tech.fax',
                'Technical Contact Phone:' => 'tech.phone',
                'Technical Contact Fax:' => 'tech.fax',
                'Technical Contact Email:' => 'tech.email',
                'Technical ID:' => 'tech.handle',
                'Technical Name:' => 'tech.name',
                'Technical Organization:' => 'tech.organization',
                'Technical Address:' => 'tech.address.street.',
                'Technical Address1:' => 'tech.address.street.',
                'Technical Address2:' => 'tech.address.street.',
                'Technical Postal Code:' => 'tech.address.pcode',
                'Technical City:' => 'tech.address.city',
                'Technical State/Province:' => 'tech.address.state',
                'Technical Country/Economy:' => 'tech.address.country',
                'Technical Phone Number:' => 'tech.phone',
                'Technical Facsimile Number:' => 'tech.fax',
                'Technical Phone:' => 'tech.phone',
                'Technical Fax:' => 'tech.fax',
                'Technical FAX:' => 'tech.fax',
                'Technical E-mail:' => 'tech.email',
                'Tech ID:' => 'tech.handle',
                'Tech Name:' => 'tech.name',
                'Tech Organization:' => 'tech.organization',
                'Tech Address:' => 'tech.address.street.',
                'Tech Address2:' => 'tech.address.street.',
                'Tech Address3:' => 'tech.address.street.',
                'Tech Street:' => 'tech.address.street.',
                'Tech Street1:' => 'tech.address.street.',
                'Tech Street2:' => 'tech.address.street.',
                'Tech Street3:' => 'tech.address.street.',
                'Tech City:' => 'tech.address.city',
                'Tech Postal Code:' => 'tech.address.pcode',
                'Tech State/Province:' => 'tech.address.state',
                'Tech Country:' => 'tech.address.country',
                'Tech Country/Economy:' => 'tech.address.country',
                'Tech Phone:' => 'tech.phone',
                'Tech FAX:' => 'tech.fax',
                'Tech Email:' => 'tech.email',
                'Tech E-mail:' => 'tech.email',
                'Billing Contact ID:' => 'billing.handle',
                'Billing Contact Name:' => 'billing.name',
                'Billing Contact Organization:' => 'billing.organization',
                'Billing Contact Address1:' => 'billing.address.street.',
                'Billing Contact Address2:' => 'billing.address.street.',
                'Billing Contact Postal Code:' => 'billing.address.pcode',
                'Billing Contact City:' => 'billing.address.city',
                'Billing Contact State/Province:' => 'billing.address.state',
                'Billing Contact Country:' => 'billing.address.country',
                'Billing Contact Phone Number:' => 'billing.phone',
                'Billing Contact Facsimile Number:' => 'billing.fax',
                'Billing Contact Email:' => 'billing.email',
                'Billing ID:' => 'billing.handle',
                'Billing Name:' => 'billing.name',
                'Billing Organization:' => 'billing.organization',
                'Billing Address:' => 'billing.address.street.',
                'Billing Address1:' => 'billing.address.street.',
                'Billing Address2:' => 'billing.address.street.',
                'Billing Address3:' => 'billing.address.street.',
                'Billing Street:' => 'billing.address.street.',
                'Billing Street1:' => 'billing.address.street.',
                'Billing Street2:' => 'billing.address.street.',
                'Billing Street3:' => 'billing.address.street.',
                'Billing City:' => 'billing.address.city',
                'Billing Postal Code:' => 'billing.address.pcode',
                'Billing State/Province:' => 'billing.address.state',
                'Billing Country:' => 'billing.address.country',
                'Billing Country/Economy:' => 'billing.address.country',
                'Billing Phone:' => 'billing.phone',
                'Billing Fax:' => 'billing.fax',
                'Billing FAX:' => 'billing.fax',
                'Billing Email:' => 'billing.email',
                'Billing E-mail:' => 'billing.email',
                'Zone ID:' => 'zone.handle',
                'Zone Organization:' => 'zone.organization',
                'Zone Name:' => 'zone.name',
                'Zone Address:' => 'zone.address.street.',
                'Zone Address 2:' => 'zone.address.street.',
                'Zone City:' => 'zone.address.city',
                'Zone State/Province:' => 'zone.address.state',
                'Zone Postal Code:' => 'zone.address.pcode',
                'Zone Country:' => 'zone.address.country',
                'Zone Phone Number:' => 'zone.phone',
                'Zone Fax Number:' => 'zone.fax',
                'Zone Email:' => 'zone.email'
            );

        $r = [];
        $disok = true;

        foreach ($rawdata as $val) {
            if (trim($val) != '') {
                if (($val[0] === '%' || $val[0] === '#') && $disok) {
                    $r['disclaimer'][] = trim(substr($val, 1));
                    $disok = true;
                    continue;
                }

                $disok = false;
                reset($items);

                foreach ($items as $match => $field) {
                    $pos = strpos($val, $match);

                    if ($pos !== false) {
                        if ($field != '') {
                            $itm = trim(substr($val, $pos + strlen($match)));

                            if ($itm != '') {
                                $r = assign($r, $field, str_replace('"', '\"', $itm));
                            }
                        }

                        if (!$scanall)
                            break;
                    }
                }
            }
        }

        array_walk_recursive($r, static function (&$v, $key){
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

        if (empty($r)) {
            if ($hasreg)
                $r['registered'] = 'no';
        }
        else {
            if ($hasreg)
                $r['registered'] = 'yes';

            $r = $this->format_dates($r, $dateformat);
        }

        return $r;
    }
}
