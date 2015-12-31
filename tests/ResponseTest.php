<?php

use phpWhois\Query;
use phpWhois\Response;

class ResponseTest extends PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $response = new Response(new Query('www.GOOGLE.com'));
        $this->assertInstanceOf(Query::class, $response->getQuery());
    }
}