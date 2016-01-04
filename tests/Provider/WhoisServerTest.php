<?php

use phpWhois\Provider\WhoisServer;
use phpWhois\Query;
use phpWhois\Response;

class WhoisServerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider constructProvider
     */
    public function testConstruct($server, $parsed)
    {
        $w = new WhoisServer(new Query('www.google.ru'), $server);

        $this->assertEquals($parsed, ['server' => $w->getServer(), 'port' => $w->getPort()]);
    }

    public function constructProvider()
    {
        return [
            ['whois.nic.ru', ['server' => 'whois.nic.ru', 'port' => 43]],
            ['whois.nic.ru:55', ['server' => 'whois.nic.ru', 'port' => 55]],
        ];
    }
}