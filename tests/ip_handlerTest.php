<?php

/**
 * Created by PhpStorm.
 * User: dreamlex
 * Date: 22.08.16
 * Time: 12:35
 */
class ip_handlerTest extends \PHPUnit\Framework\TestCase
{
    public function testParseIp()
    {
        $ipHandler = new \phpWhois\Whois();
        $result = $ipHandler->lookup('216.58.209.195');
        self::assertTrue(is_array($result));
    }
}
