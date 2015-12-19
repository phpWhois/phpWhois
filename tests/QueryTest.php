<?php

use phpWhois\Query;

class QueryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider domainsProvider
     */
    public function testGuessType($type, $domain)
    {
        $this->assertEquals($type, Query::guessType($domain));
    }

    public function domainsProvider()
    {
        return [
            [Query::QTYPE_DOMAIN,  'www.google.com'],
            //[Query::QTYPE_DOMAIN,  'президент.рф'],
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
        $this->assertEquals($optimized, Query::optimizeAddress($unoptimized));
    }

    public function optimizeProvider()
    {
        return [
            ['Help.Co.uk', 'help.co.uk'],
            ['www.help.co.uk', 'help.co.uk'],
            ['WWW.Help.co.Uk', 'help.co.uk'],
            ['WWW.SPACE', 'www.space'],
            ['www.co.uk', 'co.uk'], // Sad but true
        ];
    }
}
