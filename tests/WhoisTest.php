<?php

use phpWhois\Whois;

class WhoisTest extends \PHPUnit_Framework_TestCase
{
    public function testWhois()
    {
        $whois = new Whois;
        $result = $whois->lookup('phpwhois.pw');
        $this->assertEquals('yes', $result['regrinfo']['registered']);
    }

    /**
     * @dataProvider domainsProvider
     */
    public function testQtype($type, $domain)
    {
        $whois = new Whois;
        $this->assertEquals($type, $whois->getQueryType($domain));
    }

    public function domainsProvider()
    {
        return array(
            array(Whois::QTYPE_DOMAIN,  'www.google.com'),
            array(Whois::QTYPE_DOMAIN,  'президент.рф'),
            array(Whois::QTYPE_IPV4,    '212.212.12.12'),
            array(Whois::QTYPE_UNKNOWN, '127.0.0.1'),
            array(Whois::QTYPE_IPV6,    '1a80:1f45::ebb:12'),
            array(Whois::QTYPE_UNKNOWN, 'fc80:19c::1'),
            array(Whois::QTYPE_AS,      'ABCD_EF-GH:IJK'),
        );
    }
}
