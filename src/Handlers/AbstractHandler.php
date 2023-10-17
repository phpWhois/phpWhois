<?php

/**
 * @copyright Copyright (c) 2020 Joshua Smith
 * @license   See LICENSE file
 */

namespace phpWhois\Handlers;

use DateTime;
use DateTimeZone;
use UnexpectedValueException;

require_once __DIR__ . '/../whois.parser.php';

/**
 * AbstractHandler
 */
abstract class AbstractHandler implements HandlerInterface
{
    public $deepWhois;

    /**
     * @param string[] $lines
     * @return string[]
     */
    protected function removeBlankLines(array $lines): array
    {
        return array_filter($lines);
    }

    /**
     * @param array $data_str
     * @param array $items
     * @param string $date_format
     * @param array $translate
     * @param bool $has_org
     * @param bool $partial_match
     * @param bool $def_block
     * @return mixed
     */
    public static function easyParser( array $data_str, array $items, string $date_format, array $translate = [], bool $has_org=false, bool $partial_match=false, bool $def_block=false ){
        $r = static::getBlocks($data_str, $items, $partial_match, $def_block);
        $r = static::getContacts($r, $translate, $has_org);
        static::formatDates($r, $date_format);
        return $r;
    }

    /**
     * @param mixed  $res
     * @param string $format
     * @return array
     */
    public static function formatDates(&$res, string $format='mdy'): array
    {
        if (!is_array($res)) {
            return $res;
        }

        foreach ($res as $key => $val) {

            $key_to_ignore = (!is_numeric($key) && ($key === 'expires' || $key === 'created' || $key === 'changed'));

            if( is_array($val) ){
                if( $key_to_ignore ) {
                    $d = static::getDate($val[0], $format);
                    if ($d) {
                        $res[$key] = $d;
                    }
                } else {
                    $res[$key] = static::formatDates($val, $format);
                }
            } elseif( $key_to_ignore ){
                $d = static::getDate($val, $format);
                if( $d ){
                    $res[$key] = $d;
                }
            }
        }

        return $res;
    }

    /**
     * @param  array  $rawdata
     * @param  array  $translate
     * @param  array  $contacts
     * @param  string $main
     * @param  string $dateformat
     * @return array
     */
    public static function generic_parser_a(array $rawdata, array $translate, array $contacts, string $main='domain', string $dateformat='dmy'): array
    {
        $disclaimer = [];
        $blocks = static::generic_parser_a_blocks($rawdata, $translate, $disclaimer);

        $ret = array();
        if (isset($disclaimer) && is_array($disclaimer)) {
            $ret['disclaimer'] = $disclaimer;
        }

        if (empty($blocks) || !is_array($blocks['main'])) {
            $ret['registered'] = 'no';
            return $ret;
        }

        $r = $blocks['main'];
        $ret['registered'] = 'yes';

        foreach ($contacts as $key => $val) {
            if (isset($r[$key])) {
                if (is_array($r[$key])) {
                    $blk = $r[$key][count($r[$key]) - 1];
                } else {
                    $blk = $r[$key];
                }

                $blk = strtoupper(strtok($blk, ' '));
                if (isset($blocks[$blk])) {
                    $ret[$val] = $blocks[$blk];
                }
                unset($r[$key]);
            }
        }

        if ($main) {
            $ret[$main] = $r;
        }

        static::formatDates($ret, $dateformat);
        return $ret;
    }

    /**
     * @param array $rawdata
     * @param array $translate
     * @param array|null $disclaimer
     * @return array
     */
    public static function generic_parser_a_blocks(array $rawdata, array $translate, array &$disclaimer=[]): array
    {
        $newblock = false;
        $hasdata = false;
        $block = [];
        $blocks = [];
        $gkey = 'main';
        $dend = false;

        foreach ($rawdata as $val) {
            $val = trim($val);

            if ($val !== '' && ($val[0] === '%' || $val[0] === '#')) {
                if (!$dend) {
                    $disclaimer[] = trim(substr($val, 1));
                }
                continue;
            }
            if ($val === '') {
                $newblock = true;
                continue;
            }
            if ($newblock && $hasdata) {
                $blocks[$gkey] = $block;
                $block = [];
                $gkey = '';
            }
            $dend = true;
            $newblock = false;
            $k = trim(strtok($val, ':'));
            $v = trim(substr(strstr($val, ':'), 1));

            if ($v === '') {
                continue;
            }

            $hasdata = true;

            if (isset($translate[$k])) {
                $k = $translate[$k];
                if ($k === '') {
                    continue;
                }
                if (strpos($k, '.') !== false) {
                    $block = assign($block, $k, $v);
                    continue;
                }
            } else {
                $k = strtolower($k);
            }

            if ($k === 'handle') {
                $v = strtok($v, ' ');
                $gkey = strtoupper($v);
            }

            if( isset($block[$k]) && is_array($block[$k]) ){
                $block[$k][] = $v;
            }elseif( empty($block[$k]) ){
                $block[$k] = $v;
            }else{
                $x = $block[$k];
                unset($block[$k]);
                $block[$k][] = $x;
                $block[$k][] = $v;
            }
        }

        if ($hasdata) {
            $blocks[$gkey] = $block;
        }

        return $blocks;
    }

    /**
     * @param array $rawdata
     * @param array $items
     * @param string $dateformat
     * @param bool $hasreg
     * @param bool $scanall
     * @return array
     */
    public static function generic_parser_b( array $rawdata, array $items=[], string $dateformat='mdy', bool $hasreg=true, bool $scanall=false): array
    {
        if( empty($items) ){
            $items = [
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
            ];
        }

        $r = [];
        $disok = true;

        foreach ($rawdata as $val) {
            if (trim($val) !== '') {
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
                        if ($field !== '') {
                            $itm = trim(substr($val, $pos + strlen($match)));

                            if ($itm !== '') {
                                $r = assign($r, $field, str_replace('"', '\"', $itm));
                            }
                        }

                        if (!$scanall) {
                            break;
                        }
                    }
                }
            }
        }

        if (empty($r)) {
            if ($hasreg) {
                $r['registered'] = 'no';
            }
        } else {
            if ($hasreg) {
                $r['registered'] = 'yes';
            }

            $r = static::formatDates($r, $dateformat);
        }

        return $r;
    }

    /**
     * @param array $rawdata
     * @param array $items
     * @param bool  $partial_match
     * @param bool  $def_block
     * @return array
     */
    public static function getBlocks( array $rawdata, array $items, bool $partial_match=false, bool $def_block=false ): array
    {
        $r = [];
        $endtag = '';

        while ($val = current($rawdata)) {

            if( next($rawdata) === false ){
                // No more data
                break;
            }

            $val = trim($val);
            if ($val === '') {
                continue;
            }

            $var = $found = false;

            foreach ($items as $field => $match) {
                $pos = strpos($val, $match);

                if ($field !== '' && $pos !== false) {
                    if ($val === $match) {
                        $found = true;
                        $endtag = '';
                        $line = $val;
                        break;
                    }

                    $last = $val[strlen($val) - 1];

                    if ($last === ':' || $last === '-' || $last === ']') {
                        $found = true;
                        $endtag = $last;
                        $line = $val;
                    } else {
                        $var = strtok($field, '#');
                        $r   = assign($r, $var, trim(substr($val, $pos + strlen($match))));
                    }

                    break;
                }
            }

            if (!$found) {
                if (!$var && $def_block) {
                    $r[$def_block][] = $val;
                }
                continue;
            }

            $block = array();

            // Block found, get data ...
            while ($val = current($rawdata)) {

                if( next($rawdata) === false ){
                    // No more data
                    break;
                }

                $val = trim($val);

                if ($val === '' || $val === str_repeat($val[0], strlen($val))) {
                    continue;
                }

                $last = $val[strlen($val) - 1];

                if ($endtag === '' || $partial_match || $last === $endtag) {
                    //Check if this line starts another block
                    $et = false;

                    foreach ($items as $field => $match) {
                        $pos = strpos($val, $match);

                        if ($pos !== false && $pos === 0) {
                            $et = true;
                            break;
                        }
                    }

                    if ($et) {
                        // Another block found
                        prev($rawdata);
                        break;
                    }
                }

                $block[] = $val;
            }

            if (empty($block)) {
                continue;
            }

            foreach ($items as $field => $match) {
                $pos = strpos($line, $match);

                if ($pos !== false) {
                    $var = strtok($field, '#');
                    if ($var !== '[]') {
                        $r = assign($r, $var, $block);
                    }
                }
            }
        }

        return $r;
    }

    /**
     * @param       $array
     * @param array $extra_items
     * @param bool  $has_org
     *
     * @return mixed
     */
    public static function getContacts($array, array $extra_items=[], bool $has_org=false)
    {
        if (isset($array['billing'])) {
            $array['billing'] = static::getContact($array['billing'], $extra_items, $has_org);
        }

        if (isset($array['tech'])) {
            $array['tech'] = static::getContact($array['tech'], $extra_items, $has_org);
        }

        if (isset($array['zone'])) {
            $array['zone'] = static::getContact($array['zone'], $extra_items, $has_org);
        }

        if (isset($array['admin'])) {
            $array['admin'] = static::getContact($array['admin'], $extra_items, $has_org);
        }

        if (isset($array['owner'])) {
            $array['owner'] = static::getContact($array['owner'], $extra_items, $has_org);
        }

        if (isset($array['registrar'])) {
            $array['registrar'] = static::getContact($array['registrar'], $extra_items, $has_org);
        }

        return $array;
    }

    /**
     * @param       $array
     * @param array $extra_items
     * @param bool  $has_org
     * @return array
     */
    public static function getContact($array, array $extra_items=[], bool $has_org=false): array
    {
        if (!is_array($array)) {
            return array();
        }

        $items = [
            'fax..:' => 'fax',
            'fax.' => 'fax',
            'fax-no:' => 'fax',
            'fax -' => 'fax',
            'fax-' => 'fax',
            'fax::' => 'fax',
            'fax:' => 'fax',
            '[fax]' => 'fax',
            '(fax)' => 'fax',
            'fax' => 'fax',
            'tel. ' => 'phone',
            'tel:' => 'phone',
            'phone::' => 'phone',
            'phone:' => 'phone',
            'phone-' => 'phone',
            'phone -' => 'phone',
            'email:' => 'email',
            'e-mail:' => 'email',
            'company name:' => 'organization',
            'organisation:' => 'organization',
            'first name:' => 'name.first',
            'last name:' => 'name.last',
            'street:' => 'address.street',
            'address:' => 'address.street.',
            'language:' => '',
            'location:' => 'address.city',
            'country:' => 'address.country',
            'name:' => 'name',
            'last modified:' => 'changed'
        ];

        if (is_array($extra_items) && count($extra_items)) {
            foreach ($items as $match => $field) {
                if (!isset($extra_items[$match])) {
                    $extra_items[$match] = $field;
                }
            }
            $items = $extra_items;
        }

        $r = [];
        foreach ($array as $key => $val) {
            $ok = true;

            while ($ok) {
                reset($items);
                $ok = false;

                foreach ($items as $match => $field) {
                    $pos = stripos($val,$match);

                    if ($pos === false) {
                        continue;
                    }

                    $itm = trim(substr($val, $pos + strlen($match)));

                    if ($field !== '' && $itm !== '') {
                        $r = assign($r, $field, $itm);
                    }

                    $val = trim(substr($val, 0, $pos));

                    if ($val === '') {
                        unset($array[$key]);
                        break;
                    }

                    $array[$key] = $val;
                    $ok = true;
                }

                if (preg_match("/([+]*[-(). x0-9]){7,}/", $val, $matches)) {
                    $phone = trim(str_replace(' ', '', $matches[0]));

                    if (strlen($phone) > 8 && !preg_match('/\d{5}-\d{3}/', $phone)) {
                        if (isset($r['phone'])) {
                            if (isset($r['fax'])) {
                                continue;
                            }
                            $r['fax'] = trim($matches[0]);
                        } else {
                            $r['phone'] = trim($matches[0]);
                        }

                        $val = str_replace($matches[0], '', $val);

                        if ($val === '') {
                            unset($array[$key]);
                            continue;
                        }

                        $array[$key] = $val;
                        $ok = true;
                    }
                }

                if (preg_match('/([-0-9a-zA-Z._+&\/=]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6})/', $val, $matches)) {
                    $r['email'] = $matches[0];

                    $val = str_replace($matches[0], '', $val);
                    $val = trim(str_replace('()', '', $val));

                    if ($val === '') {
                        unset($array[$key]);
                        continue;
                    }

                    if (!isset($r['name'])) {
                        $r['name'] = $val;
                        unset($array[$key]);
                    } else {
                        $array[$key] = $val;
                    }

                    $ok = true;
                }
            }
        }

        if (!isset($r['name']) && count($array) > 0) {
            $r['name'] = array_shift($array);
        }

        if ($has_org && count($array) > 0) {
            $r['organization'] = array_shift($array);
        }

        if (isset($r['name']) && is_array($r['name'])) {
            $r['name'] = implode(' ',$r['name']);
        }

        if (!empty($array)) {
            if (isset($r['address'])) {
                $r['address'] = array_merge($r['address'], $array);
            } else {
                $r['address'] = $array;
            }
        }

        return $r;
    }

    /**
     * @param array $rawData
     *
     * @return array
     */
    protected function parseRegistryInfo(array $rawData): array
    {
        $registryItems = [
            'Registrar URL:'                 => 'referrer',
            'Registrar Name:'                => 'registrar',
            'Registrar:'                     => 'registrar',
            'Registrar Abuse Contact Email:' => 'abuse.email',
            'Registrar Abuse Contact Phone:' => 'abuse.phone',
            'Registrar WHOIS Server:'        => 'whois',
        ];

        $registryInfo = static::generic_parser_b($rawData, $registryItems);
        unset($registryInfo['registered']);

        return $registryInfo;
    }

    /**
     * @param $date
     * @param $format
     *
     * @return string|array
     */
    public static function getDate($date, $format)
    {
        $parsedDate = static::parseStandardDate($date);
        if ($parsedDate instanceof DateTime) {
            return $parsedDate->format('Y-m-d');
        }

        $months = [
            'jan' => 1,
            'ene' => 1,
            'feb' => 2,
            'mar' => 3,
            'apr' => 4,
            'abr' => 4,
            'may' => 5,
            'jun' => 6,
            'jul' => 7,
            'aug' => 8,
            'ago' => 8,
            'sep' => 9,
            'oct' => 10,
            'nov' => 11,
            'dec' => 12,
            'dic' => 12,
        ];

        $parts = explode(' ', $date);

        if (strpos($parts[0], '@') !== false) {
            unset($parts[0]);
            $date = implode(' ', $parts);
        }

        $date = str_replace([',', '.', '-', '/', "\t"], ' ', trim($date));

        $parts = explode(' ', $date);
        $res   = [];

        if ((strlen($parts[0]) === 8 || count($parts) === 1) && is_numeric($parts[0])) {
            $val = $parts[0];
            for ($p = $i = 0; $i < 3; $i++) {
                if ($format[$i] !== 'Y') {
                    $res[$format[$i]] = substr($val, $p, 2);
                    $p                += 2;
                } else {
                    $res['y'] = substr($val, $p, 4);
                    $p        += 4;
                }
            }
        } else {
            $format = strtolower($format);

            for ($p = $i = 0; $p < count($parts) && $i < strlen($format); $p++) {
                if (trim($parts[$p]) === '') {
                    continue;
                }

                if ($format[$i] !== '-') {
                    $res[$format[$i]] = $parts[$p];
                }
                $i++;
            }
        }

        if (!$res) {
            return $date;
        }

        $ok = false;

        while (!$ok) {
            $ok = true;

            foreach ($res as $key => $val) {
                if ($val === '' || $key === '') {
                    continue;
                }

                if (!is_numeric($val) && isset($months[strtolower(substr($val, 0, 3))])) {
                    $res[$key] = $res['m'];
                    $res['m']  = $months[strtolower(substr($val, 0, 3))];
                    $ok        = false;
                    break;
                }

                if ($key !== 'y' && $key !== 'Y' && $val > 1900) {
                    $res[$key] = $res['y'];
                    $res['y']  = $val;
                    $ok        = false;
                    break;
                }
            }
        }

        if ($res['m'] > 12) {
            $v = $res['m'];
            $res['m'] = $res['d'];
            $res['d'] = $v;
        }

        if ($res['y'] < 70) {
            $res['y'] += 2000;
        } elseif ($res['y'] <= 99) {
            $res['y'] += 1900;
        }

        return sprintf('%.4d-%02d-%02d', $res['y'], $res['m'], $res['d']);
    }

    /**
     * @param string $date
     *
     * @return false|DateTime
     */
    public static function parseStandardDate(string $date)
    {
        $date = trim($date);
        $UTC = new DateTimeZone('UTC');

        // Must be an array with: "pattern" => "PHP DateTime Format"
        $rules = [

            // 2020-01-01T00:00:00.0Z
            '/^(?<datetime>\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2})\.(?<microseconds>\d+)(?<timezone>Z)$/' => 'Y-m-d\TH:i:s.vT',

            // 2020-01-01T00:00:00Z
            '/^(?<datetime>\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2})(?<timezone>Z)$/' => 'Y-m-d\TH:i:sT',

            // 2020-01-01T00:00:00
            '/^(?<datetime>\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2})$/' => 'Y-m-d\TH:i:s',

            // 2021-03-03T00:00:00-0800
            '/^(?<datetime>\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[-+]\d{4})$/' => 'Y-m-d\TH:i:sP',

            // 27-Jul-2016
            '/^(?<datetime>\d{2}-[a-zA-Z]{3}-\d{4})$/' => 'd-M-Y',

            // 2020-01-01 00:00:00 CLST
            '/^(?<datetime>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}) ?(?<timezone>\w+)?$/' => 'Y-m-d H:i:s T',

            // 11-May-2016 05:18:45 UTC
             '/^(?<datetime>\d{2}-[A-Za-z]{3}-\d{4} \d{2}:\d{2}:\d{2}) (?<timezone>\w+)$/' => 'd-M-Y H:i:s T',

            // "domain-registrar AT isoc.org.il 20210913" => " 20210913"
            '/ ?(?<datetime>\d{8})( \(?[A-Za-z#\d]+\)?)?$/' => 'Ymd',

            // 20121116 16:58:21
            '/(?<datetime>\d{8} \d{2}:\d{2}:\d{2})$/' => 'Ymd H:i:s',

            // 2001/06/25 22:37:59
            '/(?<datetime>\d{4}\/\d{2}\/\d{2} \d{2}:\d{2}:\d{2})$/' => 'Y/m/d H:i:s',

            // 2019-03-31
            '/(?<datetime>\d{4}-\d{2}-\d{2})$/' => 'Y-m-d',

            // 1998/02/05
            '/(?<datetime>\d{4}\/\d{2}\/\d{2})$/' => 'Y/m/d',

            // 22.07.2023
            '/(?<datetime>\d{2}\.\d{2}\.\d{4})$/' => 'd.m.Y',

            // 31/05/1995
            // 23/08/2005 hostmaster@nic.fr
            '/(?<datetime>\d{2}\/\d{2}\/\d{4})( \w+@\w+\.\w+)?$/' => 'd/m/Y',

            // 9.12.2001 09:25:00
            // 30.6.2006 00:00:00
            '/(?<datetime>\d{1,2}\.\d{1,2}\.\d{4} \d{2}:\d{2}:\d{2})$/' => 'j.n.Y H:i:s',

            // 02.03.2018 18:52:05
            '/(?<datetime>\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}:\d{2})$/' => 'd.m.Y H:i:s',

            // Wed Apr 1 1998
            '/[A-Za-z]{3} (?<datetime>[A-Za-z]{3} \d{1,2} \d{4})$/' => 'M d Y',

            // 1996.06.27 13:36:21
            '/(?<datetime>\d{4}\.\d{2}\.\d{2} \d{2}:\d{2}:\d{2})$/' => 'Y.m.d H:i:s',

            // 01-January-2025
            '/^(?<datetime>\d{2}-[A-Z][a-z]+-\d{4})$/' => 'd-F-Y',

            // November  6 2000
            '/^(?<datetime>[A-Z][a-z]+\s+\d{1,2}\s+\d{4})$/' => 'F j Y',

        ];

        foreach( $rules as $regex => $dateTimeFormat ){
            $matches = [];

            if( preg_match($regex, $date, $matches) ){

                if( !empty($matches['microseconds']) && PHP_VERSION_ID <= 80200 ){
                    // For PHP <= 8.2, skip milliseconds
                    $date = $matches['datetime'];
                    continue;
                }

                $parsedDate = DateTime::createFromFormat($dateTimeFormat, $date, $UTC);
                if( $parsedDate instanceof DateTime ){
                    return $parsedDate;
                }

                $parsedDate = DateTime::createFromFormat($dateTimeFormat, $matches['datetime'] ?? $matches[0], $UTC);
                if( $parsedDate instanceof DateTime ){
                    return $parsedDate;
                }

                if( !empty($matches[1]) ){
                    // Fallback, try ignoring the TimeZone
                    $parsedDate = DateTime::createFromFormat('Y-m-d H:i:s', $matches[1], $UTC);
                    if( $parsedDate instanceof DateTime ){
                        return $parsedDate;
                    }
                }
            }
        }

        if( defined('DEBUG_MODE') && DEBUG_MODE ){
            throw new UnexpectedValueException("DateTime not parsable, value: \"{$date}\" ");
        }

        return false;
    }

}
