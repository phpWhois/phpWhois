<?php
/**
 * @copyright Copyright (c)2017 Joshua Smith
 * @license   GPL-2.0
 */

namespace Whois;

use PHPUnit\Framework\TestCase;

require_once '../src/whois.parser.php';

/**
 * ParserTest
 */
class ParserTest extends TestCase
{
    public function get_dateProvider()
    {
        return [
            ['date' => '1998-05-02T04:00:00Z', 'format' => 'mdy', 'expected' => '1998-02-05'],
            ['date' => '20121116 16:58:21', 'format' => 'Ymd', 'expected' => '2012-11-16'],
            ['date' => '11-May-2016 05:18:45 UTC', 'format' => 'mdy', 'expected' => '2016-05-11'],
            ['date' => '19971217', 'format' => 'Ymd', 'expected' => '1997-12-17'],
            ['date' => '19990221 #142485', 'format' => 'Ymd', 'expected' => '1999-02-21'],
            ['date' => '1998/02/05', 'format' => 'Ymd', 'expected' => '1998-02-05'],
            ['date' => '2010-04-23T09:12:48Z', 'format' => 'mdy', 'expected' => '2010-04-23'],
            // ['date' => '', 'format' => 'Ymd', 'expected' => ''],
        ];
    }

    /**
     * @param string $date
     * @param string $format
     * @param string $expected
     *
     * @return void
     *
     * @test
     * @dataProvider get_dateProvider
     */
    public function get_date(string $date, string $format, string $expected)
    {
        $actual = get_date($date, $format);

        $this->assertEquals($expected, $actual);
    }
}
