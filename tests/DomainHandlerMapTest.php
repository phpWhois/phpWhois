<?php

use phpWhois\DomainHandlerMap;
use phpWhois\Handler\Jp;


class DomainHandlerMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that findHandler returns valid classname
     *
     * @param $address
     * @param $className
     * @dataProvider addressesProvider
     */
    public function testFindHandler($address, $className)
    {
        $this->assertEquals($className, (new DomainHandlerMap)->findHandler($address));
    }

    /**
     * Addresses provider for finding handler
     *
     * @return array
     */
    public function addressesProvider()
    {
        return [
            ['www.google.jp', Jp::class],
            ['www.google.co.jp', Jp::class],
        ];
    }

    /**
     * Test that findHandler method returns null when handler not found
     *
     * @param $query
     * @dataProvider queryProviderNotDomain
     */
    public function testFindHandlerNotDomain($query)
    {
        $this->assertNull((new DomainHandlerMap)->findHandler($query));
    }

    /**
     * Missing handlers provider
     *
     * @return array
     */
    public function queryProviderNotDomain()
    {
        return [
            ['212.12.212.12'],
        ];
    }
}
