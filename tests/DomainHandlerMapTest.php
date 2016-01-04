<?php

use phpWhois\Query;
use phpWhois\DomainHandlerMap;
use phpWhois\Handler\HandlerAbstract;


class DomainHandlerMapTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider queryProvider
     */
    /*public function testFindHandler($query)
    {
        $this->assertInstanceOf(HandlerAbstract::class, DomainHandlerMap::findHandler($query));
    }

    public function queryProvider()
    {
        return [
            [new Query('www.google.ru')],
            ['www.google.ru'],
        ];
    }*/

    /**
     * @dataProvider queryProviderNotDomain
     */
    public function testFindHandlerNotDomain($query)
    {
        $this->assertNull(DomainHandlerMap::findHandler($query));
    }

    public function queryProviderNotDomain()
    {
        return [
            [new Query('212.12.212.12')],
            ['212.12.212.12'],
            [new Query()],
        ];
    }
}
