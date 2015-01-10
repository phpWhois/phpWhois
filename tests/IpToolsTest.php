<?php

use phpWhois\IpTools;

class IpToolsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validIpsProvider
     */
    public function testValidIp($ip)
    {
        $ipTools = new IpTools;
        $this->assertTrue($ipTools->validIp($ip));
    }

    public function validIpsProvider()
    {
        return array(
            array('123.123.123.123'),
            array('1a80:1f45::ebb:12'),
        );
    }

    /**
     * @dataProvider invalidIpsProvider
     */
    public function testInvalidIp($ip)
    {
        $ipTools = new IpTools;
        $this->assertFalse($ipTools->validIp($ip));
    }

    public function invalidIpsProvider()
    {
        return array(
            array(''),
            array('169.254.255.200'),
            array('172.17.255.100'),
            array('123.a15.255.100'),
            array('fd80::1'),
            array('fc80:19c::1'),
            array('1a80:1f45::ebm:12'),
            array('[1a80:1f45::ebb:12]'),
        );
    }
}
