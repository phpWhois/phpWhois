<?php

namespace handler;

include __DIR__ . '/../../src/whois.dk.php';

class dkTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $handler = new \dk_handler();
        $res     = $handler->parse($this->getWhoisData(), null);

        // Assert domain
        $this->assertEquals(array(
            'name'    => 'dtu.dk',
            'created' => '1998-03-16',
            'expires' => '2016-03-31',
            'dnssec'  => 'Signed delegation',
            'status'  => 'Active',
            // Client class overwrites nameservers.
            'nserver' => array(
                0 => 'Hostname:             a.ns.fsknet.dk',
                1 => 'Handle:               UR501-DK',
                2 => 'Hostname:             dns1.dtu.dk',
                3 => 'Handle:               D10979-DK',
                4 => 'Hostname:             dns2.dtu.dk',
                5 => 'Handle:               D10979-DK',
            ),
        ), $res['regrinfo']['domain']);

        // owner
        $this->assertEquals(array (
            'handle' => 'DTU9-DK',
            'name' => 'Danmarks Tekniske Universitet',
            'address' =>
                array (
                    'street' =>
                        array (
                            0 => 'Anker Engelunds Vej 101A',
                        ),
                    'pcode' => '2800',
                    'city' => 'Kongens Lyngby',
                    'country' => 'DK',
                ),
            'phone' => '+45 45 25 25 25',
        ), $res['regrinfo']['owner']);

        // tech
        $this->assertEquals(array (
            'handle' => 'DTU34-DK',
            'name' => 'Danmarks Tekniske Universitet',
            'address' =>
                array (
                    'street' =>
                        array (
                            0 => 'Anker Engelunds Vej 101A',
                        ),
                    'pcode' => '2800',
                    'city' => 'Kongens Lyngby',
                    'country' => 'DK',
                ),
        ), $res['regrinfo']['tech']);
    }

    private function getWhoisData()
    {
        return array(
            'rawdata'  => array(
                0  => '# Hello x.x.x.x. Your session has been logged.',
                1  => '#',
                2  => '# Copyright (c) 2002 - 2015 by DK Hostmaster A/S',
                3  => '#',
                4  => '# Version: 2.0.2',
                5  => '#',
                6  => '# The data in the DK Whois database is provided by DK Hostmaster A/S',
                7  => '# for information purposes only, and to assist persons in obtaining',
                8  => '# information about or related to a domain name registration record.',
                9  => '# We do not guarantee its accuracy. We will reserve the right to remove',
                10 => '# access for entities abusing the data, without notice.',
                11 => '# ',
                12 => '# Any use of this material to target advertising or similar activities',
                13 => '# are explicitly forbidden and will be prosecuted. DK Hostmaster A/S',
                14 => '# requests to be notified of any such activities or suspicions thereof.',
                15 => '',
                16 => 'Domain:               dtu.dk',
                17 => 'DNS:                  dtu.dk',
                18 => 'Registered:           1998-03-16',
                19 => 'Expires:              2016-03-31',
                20 => 'Registration period:  1 year',
                21 => 'VID:                  no',
                22 => 'Dnssec:               Signed delegation',
                23 => 'Status:               Active',
                24 => '',
                25 => 'Registrant',
                26 => 'Handle:               DTU9-DK',
                27 => 'Name:                 Danmarks Tekniske Universitet',
                28 => 'Address:              Anker Engelunds Vej 101A',
                29 => 'Postalcode:           2800',
                30 => 'City:                 Kongens Lyngby',
                31 => 'Country:              DK',
                32 => 'Phone:                +45 45 25 25 25',
                33 => '',
                34 => 'Administrator',
                35 => 'Handle:               DTU34-DK',
                36 => 'Name:                 Danmarks Tekniske Universitet',
                37 => 'Address:              Anker Engelunds Vej 101A',
                38 => 'Postalcode:           2800',
                39 => 'City:                 Kongens Lyngby',
                40 => 'Country:              DK',
                41 => '',
                42 => 'Nameservers',
                43 => 'Hostname:             a.ns.fsknet.dk',
                44 => 'Handle:               UR501-DK',
                45 => 'Hostname:             dns1.dtu.dk',
                46 => 'Handle:               D10979-DK',
                47 => 'Hostname:             dns2.dtu.dk',
                48 => 'Handle:               D10979-DK',
                49 => '',
            ),
            'regyinfo' => array(),
        );
    }
}