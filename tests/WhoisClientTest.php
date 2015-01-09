<?php

use phpWhois\WhoisClient;

class WhoisClientTest extends \PHPUnit_Framework_TestCase
{
    public function testVersion() {
        $client = new WhoisClient;
        $this->assertRegExp('/^(\d+)\.(\d+)\.(\d+)(-\w+)*$/', $client->codeVersion);
    }
}