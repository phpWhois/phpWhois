<?php

use phpWhois\Whois;

class WhoisTest extends \PHPUnit_Framework_TestCase
{
    public function testWhois() {
        $whois = new Whois;
        $result = $whois->lookup('phpwhois.pw');
        $this->assertEquals('yes', $result['regrinfo']['registered']);
    }

    public function testQtypeDomain() {
        $whois = new Whois;
        $this->assertEquals(Whois::QTYPE_DOMAIN, $whois->getQueryType('www.google.com'));
    }

    public function testQtypeDomainIdn() {
        $whois = new Whois;
        $this->assertEquals(Whois::QTYPE_DOMAIN, $whois->getQueryType('президент.рф'));
    }

    public function testQtypeIpv4() {
        $whois = new Whois;
        $this->assertEquals(Whois::QTYPE_IPV4, $whois->getQueryType('212.212.12.12'));
    }

    public function testQtypeIpv4Invalid() {
        $whois = new Whois;
        $this->assertEquals(Whois::QTYPE_UNKNOWN, $whois->getQueryType('127.0.0.1'));
    }

    public function testQtypeIpv6() {
        $whois = new Whois;
        $this->assertEquals(Whois::QTYPE_IPV6, $whois->getQueryType('1a80:1f45::ebb:12'));
    }

    public function testQtypeIpv6Invalid() {
        $whois = new Whois;
        $this->assertEquals(Whois::QTYPE_UNKNOWN, $whois->getQueryType('fc80:19c::1'));
    }

    public function testQtypeAS() {
        $whois = new Whois;
        $this->assertEquals(Whois::QTYPE_AS, $whois->getQueryType('ABCD_EF-GH:IJK'));
    }
}
