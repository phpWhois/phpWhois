<?php

use phpWhois\Whois;
use phpWhois\Handler\HandlerBase;

/**
 * Class WhoisTest
 */
class WhoisTest extends PHPUnit_Framework_TestCase
{
    protected $whois;

    protected function setUp()
    {
        $this->whois = new Whois('google.com');
    }

    /**
     * Pass null as a handler class name
     *
     */
    public function testSetHandlerNull()
    {
        $this->assertInstanceOf(Whois::class, $this->whois->setHandler(null));
    }

    /**
     * Try to assign a handler while query is not set
     *
     * @expectedException InvalidArgumentException
     */
    public function testSetHandlerEmptyQuery()
    {
        $this->markTestIncomplete('Should handler test double be here?');
        $whois = new Whois();
        $whois->setHandler(HandlerBase::class);
    }

    /**
     * Try to set correct handler
     */
    public function testSetHandler()
    {
        $this->markTestIncomplete('Should handler test double be here?');

        $method = new \ReflectionMethod(Whois::class, 'getHandler');
        $method->setAccessible(true);

        $this->whois->setHandler(HandlerBase::class);

        $this->assertInstanceOf(HandlerBase::class, $method->invoke($this->whois, 'getHandler'));
    }

    /**
     * Set handler of wrong type
     *
     * @expectedException InvalidArgumentException
     */
    public function testSetHandlerWrongType()
    {
        $this->markTestIncomplete('Should test double be here?');

        $method = new \ReflectionMethod(Whois::class, 'getHandler');
        $method->setAccessible(true);

        $this->whois->setHandler('stdClass');

        $this->assertInstanceOf(HandlerBase::class, $method->invoke($this->whois, 'getHandler'));

    }

    /**
     * TODO: lookup test
     */
}