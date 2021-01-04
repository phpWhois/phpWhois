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

use phpWhois\BaseTestCase;

/**
 * HandlerTest
 */
abstract class HandlerTest extends BaseTestCase
{
    /**
     * @param string $which
     *
     * @return string[]
     * @throws \InvalidArgumentException
     */
    protected function loadFixture(string $which): array
    {
        $fixture = sprintf(
            '%s/fixtures/%s.txt',
            \dirname(__DIR__),
            $which
        );
        if (file_exists($fixture)) {
            $raw = file_get_contents($fixture);

            // Testing on Windows introduces carriage returns
            $raw = str_replace("\r", '', $raw);

            // Split the lines the same way as WhoisClient::getRawData()
            return explode("\n", $raw);
        }

        throw new \InvalidArgumentException("Cannot find fixture `{$fixture}`");
    }
}
