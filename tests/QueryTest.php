<?php

use phpWhois\Query;

class QueryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Pass query params to constructor and see if they were assigned correctly
     */
    public function testConstructorWithParams()
    {
        $params = ['param1', 'param2'];
        $query = new Query(null, $params);
        $this->assertEquals($params, $query->getParams());
    }
    /**
     * @dataProvider addressesProvider
     */
    public function testGuessType($type, $domain)
    {
        $this->assertEquals($type, (new Query())->guessType($domain));
    }

    /**
     * Return addresses of all possible types
     *
     * @return array
     */
    public function addressesProvider()
    {
        return [
            [Query::QTYPE_DOMAIN,  'www.GooGLe.com'],
            [Query::QTYPE_DOMAIN,  'ПРЕЗиДЕНТ.рф'],
            [Query::QTYPE_IPV4,    '212.212.12.12'],
            [Query::QTYPE_UNKNOWN, '127.0.0.1'],
            [Query::QTYPE_IPV6,    '1a80:1f45::ebb:12'],
            [Query::QTYPE_UNKNOWN, 'fc80:19c::1'],
            [Query::QTYPE_AS,      'ABCD_EF-GH:IJK'],
        ];
    }

    /**
     * @dataProvider optimizeProvider
     */
    public function testOptimizeAddress($unoptimized, $optimized)
    {
        $this->assertEquals($optimized, (new Query())->optimizeAddress($unoptimized));
    }

    public function optimizeProvider()
    {
        return [
            ['Help.Co.uk', 'help.co.uk'],
            ['www.help.co.uk', 'help.co.uk'],
            ['WWW.Help.co.Uk', 'help.co.uk'],
            ['WWW.SPACE', 'www.space'],
            ['www.co.uk', 'co.uk'], // Sad but true
            ['wWw.ПРЕЗИДент.рФ', 'xn--d1abbgf6aiiy.xn--p1ai'],
        ];
    }
}
