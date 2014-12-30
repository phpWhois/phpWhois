<?php

use phpWhois\IpTools;

class IpToolsTest extends \PHPUnit_Framework_TestCase
{
    public function testIpEmpty() {
        $ipTools = new IpTools;
        $this->assertFalse($ipTools->validIp(''));
    }

    public function testIpv4() {
        $ipTools = new IpTools;
        $this->assertTrue($ipTools->validIp('123.123.123.123'));
    }

    public function testIpv4Reserved() {
        $ipTools = new IpTools;
        $this->assertFalse($ipTools->validIp('169.254.255.200'));
    }

    public function testIpv4Private() {
        $ipTools = new IpTools;
        $this->assertFalse($ipTools->validIp('172.17.255.100'));
    }

    public function testIpv4Invalid() {
        $ipTools = new IpTools;
        $this->assertFalse($ipTools->validIp('123.a15.255.100'));
    }

    public function testIpv6() {
        $ipTools = new IpTools;
        $this->assertTrue($ipTools->validIp('1a80:1f45::ebb:12'));
    }

    public function testIpv6Reserved1() {
        $ipTools = new IpTools;
        $this->assertFalse($ipTools->validIp('fd80::1'));
    }

    public function testIpv6Reserved2() {
        $ipTools = new IpTools;
        $this->assertFalse($ipTools->validIp('fc80:19c::1'));
    }

    public function testIpv6Invalid() {
        $ipTools = new IpTools;
        $this->assertFalse($ipTools->validIp('1a80:1f45::ebm:12'));
    }
}
