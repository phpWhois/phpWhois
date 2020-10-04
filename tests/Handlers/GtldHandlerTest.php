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
 * @copyright Copyright (c) 2018 Joshua Smith
 */

namespace phpWhois\Handlers;

use DMS\PHPUnitExtensions\ArraySubset\Assert;

/**
 * GtldHandlerTest
 */
class GtldHandlerTest extends HandlerTest
{
    /**
     * @var \gtld_handler $handler
     */
    protected $handler;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->handler            = new \gtld_handler();
        $this->handler->deepWhois = false;
    }

    /**
     * @return void
     *
     * @test
     */
    public function parseGoogleDotCom()
    {
        $query = 'google.com';

        $fixture = $this->loadFixture($query);
        $data    = [
            "rawdata"  => $fixture,
            "regyinfo" => [],
        ];

        $actual = $this->handler->parse($data, $query);

        $expected = [
            'domain'     => [
                'name'    => 'GOOGLE.COM',
                'changed' => '2011-07-20',
                'created' => '1997-09-15',
                'expires' => '2020-09-14',
            ],
            'registered' => 'yes',
        ];

        Assert::assertArraySubset($expected, $actual['regrinfo'], 'Whois data may have changed');
        Assert::assertArraySubset($fixture, $actual['rawdata'], 'Fixture data may be out of date');
    }
}
