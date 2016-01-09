<?php

use phpWhois\QueryUtils;

class QueryUtilsTest extends \PHPUnit_Framework_TestCase
{
    protected $q;

    protected function setUp()
    {
        $this->q = new QueryUtils();
    }

    /**
     * Test if given ipv4 address is valid
     *
     * @param string $ip
     * @return string
     * @dataProvider validIpv4Provider
     */
    public function testValidIpIpv4($ip)
    {
        $this->assertTrue($this->q->validIp($ip, 'ipv4'));
        return $ip;
    }

    /**
     * Provides only ipv4 valid addresses
     *
     * @return array
     */
    public function validIpv4Provider()
    {
        return [
            ['123.123.123.123'],
        ];
    }

    /**
     * Test if given ipv6 address is valid
     *
     * @param string $ip
     * @return string
     * @dataProvider validIpv6Provider
     */
    public function testValidIpIpv6($ip)
    {
        $this->assertTrue($this->q->validIp($ip, 'ipv6'));
        return $ip;
    }

    /**
     * Provides only ipv6 valid addresses
     *
     * @return array
     */
    public function validIpv6Provider()
    {
        return [
            ['1a80:1f45::ebb:12'],
        ];
    }

    /**
     * Test if given ipv4 or ipv6 is valid
     *
     * @param string $ip
     * @dataProvider validIpProvider
     */
    public function testValidIpAny($ip)
    {
        $this->assertTrue($this->q->validIp($ip));
    }

    /**
     * Provides both ipv4 and ipv6 valid addresses
     *
     * @return array
     */
    public function validIpProvider()
    {
        return array_merge($this->validIpv4Provider(), $this->validIpv6Provider());;
    }

    /**
     * @dataProvider invalidIpsProvider
     */
    public function testInvalidIp($ip)
    {
        $this->assertFalse($this->q->validIp($ip));
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
        $this->assertTrue($this->q->validDomain($domain));
    }

    public function validDomainsProvider()
    {
        return [
            ['domain.space'],
            ['www.domain.space'],
            ['sub.domain.space'],
            ['www.sub.domain.space'],
            ['domain.CO.uk'],
            ['www.domain.co.uk'],
            ['sub.www.domain.co.uk'],
            ['президент.рф'],
            ['www.ПРЕЗИДЕНТ.рф'],
            ['xn--e1afmkfd.xn--80akhbyknj4f'],
        ];
    }

    /**
     * @dataProvider invalidDomainsProvider
     */
    public function testInvalidDomain($domain)
    {
        $this->assertFalse($this->q->validDomain($domain));
    }

    public function invalidDomainsProvider()
    {
        return [
            ['212.12.212.12'],
            ['domain'],
            ['domain.1com'],
            ['domain.co.u'],
            ['xn--e1afmkfd.xn--80akhb.yknj4f'],
            ['xn--e1afmkfd.xn--80akhbyknj4f.'],
            ['президент.рф.'],
            ['президент.рф2'],
        ];
    }

    /**
     * @dataProvider validASProvider
     */
    public function testValidAs($as)
    {
        $this->assertTrue($this->q->validAS($as));
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
    public function testInvalidAs($as)
    {
        $this->assertFalse($this->q->validAS($as));
    }

    public function invalidASProvider()
    {
        return [
            ['ЯЯ-12345'],
        ];
    }
}
