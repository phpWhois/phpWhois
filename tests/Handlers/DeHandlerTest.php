<?php
/**
 * @copyright Copyright (c) 2020 Joshua Smith
 * @license   See LICENSE file
 */

namespace Tests\Handlers;

use DMS\PHPUnitExtensions\ArraySubset\Assert;
use phpWhois\Handlers\DeHandler;

/**
 * DeHandlerTest
 */
class DeHandlerTest extends AbstractHandler
{
    /**
     * @var DeHandler $handler
     */
    protected $handler;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->handler            = new DeHandler();
        $this->handler->deepWhois = false;
    }

    /**
     * @return void
     *
     * @test
     */
    public function parse4EverDotDe()
    {
        $query = '4ever.de';

        $fixture = $this->loadFixture($query);
        $data    = [
            'rawdata'  => $fixture,
            'regyinfo' => [],
        ];

        $actual = $this->handler->parse($data, $query);

        $expected = [
            'domain' =>
                [
                    'name' => '4ever.de',
                    'nserver' =>
                        [
                            0 => 'ns2.4ever.de 104.156.233.7 2001:19f0:5800:8101:0:0:0:235',
                            1 => 'ns3.4ever.de 107.170.225.117 2604:a880:1:20:0:0:26:3001',
                            2 => 'ns.4ever.de 213.239.225.238 2a01:4f8:222:1b01:0:0:eb:238',
                            3 => 'ns.does.not-exist.de',
                        ],
                    'status' => 'connect',
                ],
            'registered' => 'yes',
        ];

        Assert::assertArraySubset($expected, $actual['regrinfo'], 'Whois data may have changed');
        $this->assertArrayHasKey('rawdata', $actual);
        Assert::assertArraySubset($fixture, $actual['rawdata'], 'Fixture data may be out of date');
    }

    /**
     * @return void
     *
     * @test
     */
    public function parseGoogleDotDe()
    {
        $query = 'google.de';

        $fixture = $this->loadFixture($query);
        $data    = [
            'rawdata'  => $fixture,
            'regyinfo' => [],
        ];

        $actual = $this->handler->parse($data, $query);

        $expected = [
            'domain' => [
                'name' => 'google.de',
                'nserver' => [
                    0 => 'ns1.google.com',
                    1 => 'ns2.google.com',
                    2 => 'ns3.google.com',
                    3 => 'ns4.google.com',
                ],
                'status' => 'connect',
            ],
            'registered' => 'yes',
        ];

        Assert::assertArraySubset($expected, $actual['regrinfo'], 'Whois data may have changed');
        $this->assertArrayHasKey('rawdata', $actual);
        Assert::assertArraySubset($fixture, $actual['rawdata'], 'Fixture data may be out of date');
    }

    /**
     * @return void
     *
     * @test
     */
    public function parseDenicDotDe()
    {
        $query = 'denic.de';

        $fixture = $this->loadFixture($query);
        $data    = [
            'rawdata'  => $fixture,
            'regyinfo' => [],
        ];

        $actual = $this->handler->parse($data, $query);

        $expected = [
            'domain' => [
                'name' => 'denic.de',
                'nserver' => [
                    0 => 'ns1.denic.de 77.67.63.106 2001:668:1f:11:0:0:0:106',
                    1 => 'ns2.denic.de 81.91.164.6 2a02:568:0:2:0:0:0:54',
                    2 => 'ns3.denic.de 195.243.137.27 2003:8:14:0:0:0:0:106',
                    3 => 'ns4.denic.net',
                ],
                'status' => 'connect',
            ],
            'registered' => 'yes',
        ];

        Assert::assertArraySubset($expected, $actual['regrinfo'], 'Whois data may have changed');
        $this->assertArrayHasKey('rawdata', $actual);
        Assert::assertArraySubset($fixture, $actual['rawdata'], 'Fixture data may be out of date');
    }

    /**
     * @return void
     *
     * @test
     */
    public function parseDomainInConnectStatus()
    {
        $query = 'humblebundle.de';

        $fixture = $this->loadFixture($query);
        $data    = [
            'rawdata'  => $fixture,
            'regyinfo' => [],
        ];

        $actual = $this->handler->parse($data, $query);

        $expected = [
            'domain' => [
                'name' => 'humblebundle.de',
                'nserver' => [
                    0 => 'ns1.sedoparking.com',
                    1 => 'ns2.sedoparking.com',
                ],
                'status' => 'connect',
            ],
            'registered' => 'yes',
        ];

        Assert::assertArraySubset($expected, $actual['regrinfo'], 'Whois data may have changed');
        $this->assertArrayHasKey('rawdata', $actual);
        Assert::assertArraySubset($fixture, $actual['rawdata'], 'Fixture data may be out of date');
    }

    /**
     * @return void
     *
     * @test
     */
    public function parseDomainInFreeStatus()
    {
        $query = 'a2ba91bff88c6983f6af010c41236206df64001d.de';

        $fixture = $this->loadFixture($query);
        $data    = [
            'rawdata'  => $fixture,
            'regyinfo' => [],
        ];

        $actual = $this->handler->parse($data, $query);

        $expected = [
            'domain'     => [
                'name'   => 'a2ba91bff88c6983f6af010c41236206df64001d.de',
            ],
            'registered' => 'no',
        ];

        Assert::assertArraySubset($expected, $actual['regrinfo'], 'Whois data may have changed');
        $this->assertArrayHasKey('rawdata', $actual);
        Assert::assertArraySubset($fixture, $actual['rawdata'], 'Fixture data may be out of date');
    }
}
