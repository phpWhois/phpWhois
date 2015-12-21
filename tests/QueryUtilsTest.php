<?php

use phpWhois\QueryUtils;

class QueryUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validIpsProvider
     */
    public function testValidIp($ip)
    {
        $this->assertTrue(QueryUtils::validIp($ip));
    }

    public function validIpsProvider()
    {
        return [
            ['123.123.123.123'],
            ['1a80:1f45::ebb:12'],
        ];
    }

    /**
     * @dataProvider invalidIpsProvider
     */
    public function testInvalidIp($ip)
    {
        $this->assertFalse(QueryUtils::validIp($ip));
    }

    public function invalidIpsProvider()
    {
        return [
            [''],
            ['169.254.255.200'],
            ['172.17.255.100'],
            ['123.a15.255.100'],
            ['fd80::1'],
            ['fc80:19c::1'],
            ['1a80:1f45::ebm:12'],
            ['[1a80:1f45::ebb:12]'],
        ];
    }

    /**
     * @dataProvider validDomainsProvider
     */
    public function testValidDomain($domain)
    {
        $this->assertTrue(QueryUtils::validDomain($domain));
    }

    public function validDomainsProvider()
    {
        return [
            ['domain.space'],
            ['www.domain.space'],
            ['sub.domain.space'],
            ['www.sub.domain.space'],
            ['domain.co.uk'],
            ['www.domain.co.uk'],
            ['sub.www.domain.co.uk']
        ];
    }

    /**
     * @dataProvider invalidDomainsProvider
     */
    public function testInvalidDomain($domain)
    {
        $this->assertFalse(QueryUtils::validDomain($domain));
    }

    public function invalidDomainsProvider()
    {
        return [
            ['212.12.212.12'],
            ['domain'],
            ['domain.1com'],
            ['domain.abcdefg'], // 7 symbols TLD
            ['domain.co.u'],
        ];
    }

    /**
     * @dataProvider validASProvider
     */
    public function testValidAS($as)
    {
        $this->assertTrue(QueryUtils::validAS($as));
    }

    public function validASProvider()
    {
        return [
            ['AS-13245'],
            ['aS-12345']
        ];
    }

    /**
     * @dataProvider invalidASProvider
     */
    public function testInvalidAS($as)
    {
        $this->assertFalse(QueryUtils::validAS($as));
    }

    public function invalidASProvider()
    {
        return [
            ['ЯЯ-12345'],
        ];
    }
}
