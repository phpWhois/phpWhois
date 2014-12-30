<?php

use phpWhois\Whois;

class WhoisTest extends \PHPUnit_Framework_TestCase
{
    public function testWhois() {
        $whois = new Whois;
        $result = $whois->lookup('phpwhois.pw');
        $this->assertEquals('yes', $result['regrinfo']['registered']);
    }
}