<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

use DMS\PHPUnitExtensions\ArraySubset\Assert;

/**
 * AeroHandlerTest
 */
class AeroHandlerTest extends HandlerTest
{
    /**
     * @var AeroHandler $handler
     */
    protected $handler;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->handler            = new AeroHandler();
        $this->handler->deepWhois = false;
    }

    /**
     * @return void
     *
     * @test
     */
    public function parseNicDotAero()
    {
        $query = 'nic.aero';

        $fixture = $this->loadFixture($query);
        $data    = [
            'rawdata'  => $fixture,
            'regyinfo' => [],
        ];

        $actual = $this->handler->parse($data, $query);

        $expected = [
            'domain'     => [
                'name'    => 'NIC.AERO',
                'changed' => '2020-03-08',
                'created' => '2002-03-08',
                'expires' => '2021-03-08',
            ],
            'registered' => 'yes', // Currently broken
        ];

        Assert::assertArraySubset($expected, $actual['regrinfo'], 'Whois data may have changed');
        $this->assertArrayHasKey('rawdata', $actual);
        Assert::assertArraySubset($fixture, $actual['rawdata'], 'Fixture data may be out of date');
    }
}
