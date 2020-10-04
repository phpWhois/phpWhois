<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

use DMS\PHPUnitExtensions\ArraySubset\Assert;

/**
 * AmHandlerTest
 */
class AmHandlerTest extends HandlerTest
{
    /**
     * @var AmHandler $handler
     */
    protected $handler;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->handler            = new AmHandler();
        $this->handler->deepWhois = false;
    }

    /**
     * @return void
     *
     * @test
     */
    public function parseIsocDotAm()
    {
        $query = 'isoc.am';

        $fixture = $this->loadFixture($query);
        $data    = [
            'rawdata'  => $fixture,
            'regyinfo' => [],
        ];

        $actual = $this->handler->parse($data, $query);

        $expected = [
            'domain'     => [
                'name'    => 'isoc.am',
                'changed' => '2018-03-27',
                'created' => '2000-01-01',
            ],
            'registered' => 'yes',
        ];

        Assert::assertArraySubset($expected, $actual['regrinfo'], 'Whois data may have changed');
        $this->assertArrayHasKey('rawdata', $actual);
        Assert::assertArraySubset($fixture, $actual['rawdata'], 'Fixture data may be out of date');
    }
}
