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

/**
 * EduHandlerTest
 */
class EduHandlerTest extends HandlerTest
{
    /**
     * @var \edu_handler $handler
     */
    protected $handler;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->handler            = new \edu_handler();
        $this->handler->deepWhois = false;
    }

    /**
     * @return void
     *
     * @test
     */
    public function parseBerkeleyDotEdu()
    {
        $query = 'berkeley.edu';

        $fixture = $this->loadFixture($query);
        $data    = [
            'rawdata'  => $fixture,
            'regyinfo' => [],
        ];

        $actual = $this->handler->parse($data, $query);

        $this->assertEquals('berkeley.edu', $actual['regrinfo']['domain']['name']);
        $this->assertEquals('2020-04-23', $actual['regrinfo']['domain']['changed']);
        $this->assertEquals('1985-04-24', $actual['regrinfo']['domain']['created']);
        $this->assertEquals('2020-07-31', $actual['regrinfo']['domain']['expires']);
        // $this->assertEquals('yes', $actual['regrinfo']['registered']);

        $this->assertArrayHasKey('rawdata', $actual);
        $this->assertEquals($fixture, $actual['rawdata'], 'Fixture data may be out of date');
    }
}
