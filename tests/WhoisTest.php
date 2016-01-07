<?php

use phpWhois\Whois;
use phpWhois\Query;
use phpWhois\Handler\HandlerBase;

class WhoisTest extends PHPUnit_Framework_TestCase
{
    /**
     * Pass null as handler class name
     *
     * @expectedException InvalidArgumentException
     */
    public function testSetHandlerNull()
    {
        $whois = new Whois();
        $this->assertInstanceOf(Whois::class, $whois->setHandler(null));
        $this->assertInstanceOf(Query::class, $whois->getQuery());
    }

    /**
     * Try to assign a handler while query is not set
     *
     * @expectedException InvalidArgumentException
     */
    public function testSetHandlerEmptyQuery()
    {
        $whois = new Whois();
        $whois->setHandler(HandlerBase::class);
    }

    public function testSetHandler()
    {
        $method = new \ReflectionMethod(Whois::class, 'getHandler');
        $method->setAccessible(true);

        $whois = new Whois('google.com');
        $whois->setHandler(HandlerBase::class);

        $this->assertInstanceOf(HandlerBase::class, $method->invoke($whois, 'getHandler'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetHandlerInvalid()
    {
        $method = new \ReflectionMethod(Whois::class, 'getHandler');
        $method->setAccessible(true);

        $whois = new Whois('google.com');
        $whois->setHandler('stdClass');

        $this->assertInstanceOf(HandlerBase::class, $method->invoke($whois, 'getHandler'));
    }

    /**
     * TODO: lookup test
     */
}