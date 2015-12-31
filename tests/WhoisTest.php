<?php

use phpWhois\Whois;

class WhoisTest extends PHPUnit_Framework_TestCase
{
    public function testSetHandlerEmpty()
    {
        $whois = new Whois();
        $this->assertInstanceOf(Whois::class, $whois->setHandler());
    }

    public function testSetHandlerNull()
    {
        $whois = new Whois();
        $this->assertInstanceOf(Whois::class, $whois->setHandler(null));
    }
}