<?php
/**
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @license
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace Tests\Handlers;

use DMS\PHPUnitExtensions\ArraySubset\Assert;
use phpWhois\Handlers\FjHandler;

/**
 * FjHandlerTest
 */
class FjHandlerTest extends AbstractHandler
{
    /**
     * @var FjHandler $handler
     */
    protected $handler;

    /**
     * @return void
     * @noinspection PhpUnreachableStatementInspection
     */
    protected function setUp(): void
    {
        self::markTestSkipped('.fj domain parsing broken');

        parent::setUp();

        $this->handler            = new FjHandler();
        $this->handler->deepWhois = false;
    }

    /**
     * @test
     * @return void
     */
    public function parseFijiDotGovDotFj(): void
    {
        $query = 'fiji.gov.fj';

        $fixture = $this->loadFixture($query);
        $data    = [
            'rawdata'  => $fixture,
            'regyinfo' => [],
        ];

        $actual = $this->handler->parse($data, $query);

        $expected = [
            'domain'     => [
                'name'    => 'fiji.gov.fj',
                // 'changed' => '2020-08-03',
                // 'created' => '2003-03-10',
                'expires' => '2020-12-31',
            ],
            'registered' => 'yes',
        ];

        Assert::assertArraySubset($expected, $actual['regrinfo'], 'Whois data may have changed');
        $this->assertArrayHasKey('rawdata', $actual);
        Assert::assertArraySubset($fixture, $actual['rawdata'], 'Fixture data may be out of date');
    }
}
