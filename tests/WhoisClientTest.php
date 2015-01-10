<?php

use phpWhois\WhoisClient;

class WhoisClientTest extends \PHPUnit_Framework_TestCase
{
    public function testVersion()
    {
        $client = new WhoisClient;
        $this->assertRegExp('/^(\d+)\.(\d+)\.(\d+)(-\w+)*$/', $client->codeVersion);
    }

    /**
     * @dataProvider serversProvider
     */
    public function testParseServer($server, $result)
    {
        $whoisClient = new WhoisClient;
        $this->assertEquals($result, $whoisClient->parseServer($server));
    }

    public function serversProvider()
    {
        return array(
            array('http://www.phpwhois.pw:80/', array('scheme' => 'http', 'host' => 'www.phpwhois.pw', 'port' => 80)),
            array('http://www.phpwhois.pw:80', array('scheme' => 'http', 'host' => 'www.phpwhois.pw', 'port' => 80)),
            array('http://www.phpwhois.pw', array('scheme' => 'http', 'host' => 'www.phpwhois.pw')),
            array('www.phpwhois.pw:80', array('host' => 'www.phpwhois.pw', 'port' => 80)),
            array('www.phpwhois.pw:80/', array('host' => 'www.phpwhois.pw', 'port' => 80)),
            array('www.phpwhois.pw', array('host' => 'www.phpwhois.pw')),
            array('www.phpwhois.pw/', array('host' => 'www.phpwhois.pw')),
            array('http://127.0.0.1:80/', array('scheme' => 'http', 'host' => '127.0.0.1', 'port' => 80)),
            array('http://127.0.0.1:80', array('scheme' => 'http', 'host' => '127.0.0.1', 'port' => 80)),
            array('http://127.0.0.1', array('scheme' => 'http', 'host' => '127.0.0.1')),
            array('127.0.0.1:80', array('host' => '127.0.0.1', 'port' => 80)),
            array('127.0.0.1:80/', array('host' => '127.0.0.1', 'port' => 80)),
            array('127.0.0.1', array('host' => '127.0.0.1')),
            array('127.0.0.1/', array('host' => '127.0.0.1')),
            array('http://[1a80:1f45::ebb:12]:80/', array('scheme' => 'http', 'host' => '[1a80:1f45::ebb:12]', 'port' => 80)),
            array('http://[1a80:1f45::ebb:12]:80', array('scheme' => 'http', 'host' => '[1a80:1f45::ebb:12]', 'port' => 80)),
            array('http://[1a80:1f45::ebb:12]', array('scheme' => 'http', 'host' => '[1a80:1f45::ebb:12]')),
            //array('http://1a80:1f45::ebb:12', array('scheme' => 'http', 'host' => '[1a80:1f45::ebb:12]')),
            array('[1a80:1f45::ebb:12]:80', array('host' => '[1a80:1f45::ebb:12]', 'port' => 80)),
            array('[1a80:1f45::ebb:12]:80/', array('host' => '[1a80:1f45::ebb:12]', 'port' => 80)),
            array('1a80:1f45::ebb:12', array('host' => '[1a80:1f45::ebb:12]')),
            array('1a80:1f45::ebb:12/', array('host' => '[1a80:1f45::ebb:12]')),
        );
    }
}
