<?php

use phpWhois\Handler\HandlerBase;
use phpWhois\Provider\ProviderAbstract;
use phpWhois\Query;

class HandlerBaseMock extends HandlerBase {
    public $server = 'whois.server.test';
}

class HandlerBaseTest extends \PHPUnit_Framework_TestCase
{
    protected $query;
    protected $handler;

    public function setUp()
    {
        $this->query = new Query('www.goOgle.com');
        $this->handler = new HandlerBaseMock($this->query);
    }

    public function testConstructorWithoutServer()
    {
        $handler = new HandlerBaseMock($this->query);

        $this->assertEquals('whois.server.test', $handler->server);

        $this->assertInstanceOf(ProviderAbstract::class, $handler->getProvider());
    }

    /**
     * Test constructor with all possible parameters
     */
    public function testConstructorWithServer()
    {
        $server = 'special.whois.server.test';

        $handler = new HandlerBaseMock($this->query, $server);

        $this->assertEquals($server, $handler->server);

        $this->assertInstanceOf(ProviderAbstract::class, $handler->getProvider());
    }

    /**
     * TODO: Test Provider setting by class name
     */

    /**
     * Test splitting raw data by newline
     *
     * @param $raw  Raw data
     * @param $count Number of rows
     * @dataProvider rawProvider
     */
    public function testSplitRows($raw, $count)
    {
        $this->assertCount($count, $this->handler->splitRows($raw));
    }

    public function rawProvider()
    {
        return [
            ["line1\nline2\nline3", 3],
            ["line1\r\nline2\r\nline3", 3],
            ["line1\r\nline2\r\nline3", 3],
            ["line1\r\n\r\nline2\r\nline3", 4],
            ["line1\r\n\n\r\nline2\r\nline3", 5],
        ];
    }
}
