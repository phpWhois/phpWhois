<?php

/**
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
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
 * @link http://phpwhois.pw
 * @copyright Copyright (c) 2015 Dmitry Lukashin
 */

namespace phpWhois;

class DomainHandlerMap
{
    /**
     * @var array   Mappings from domain name to handler class
     */
    private $map = [
        '/\.bg$/i' => Handler\Bg::class,
        '/\.fr$/i' => Handler\Registrar\Frnic::class,
        '/\.hm$/i' => Handler\Hm::class,
        '/\.hu$/i' => Handler\Hu::class,
        '/\.im$/i' => Handler\Im::class,
        '/\.jp$/i' => Handler\Jp::class,
        '/\.kr$/i' => Handler\Kr::class,
        '/\.(kz|xn--80ao21a)$/i' => Handler\Kz::class, //.қаз
        '/\.pf$/i' => Handler\Pf::class,
        '/\.pl$/i' => Handler\Pl::class,
        '/\.pm$/i' => Handler\Registrar\Frnic::class,
        '/\.pt$/i' => Handler\Pt::class,
        '/\.re$/i' => Handler\Registrar\Frnic::class,
        '/\.(ru|su|xn--p1ai)$/i' => Handler\Ru::class, //.рф
        '/\.sk$/i' => Handler\Sk::class,
        '/\.sm$/i' => Handler\Sm::class,
        '/\.tf$/i' => Handler\Registrar\Frnic::class,
        '/\.uy$/i' => Handler\Uy::class,
        '/\.wf$/i' => Handler\Registrar\Frnic::class,
        '/\.yt$/i' => Handler\Registrar\Frnic::class,
//        '/^(?:[a-z0-9\-]+?\.){1,2}ru$/i' => Handler\Registrar\NicRu::class,
//        '/^(?:[a-z0-9\-]+?\.){1,2}su$/i' => Handler\Registrar\NicRu::class,
//        '/^(?:[a-z0-9\-]+?\.){1}ru\.net$/i' => Handler\Registrar\NicRu::class,
//        '/^(?:[a-z0-9\-]+?\.){1}\.moscow$/i' => Handler\Registrar\NicRu::class,
    ];

    /**
     * Return map pattern => handler
     *
     * @return array
     */
    protected function getMap()
    {
        return $this->map;
    }

    /**
     * Look for domain handler in the predefined map
     *
     * @param string $address
     *
     * @return string|null
     */
    public function findHandler($address)
    {
        foreach ($this->getMap() as $pattern => $class) {
            if (preg_match($pattern, $address)) {
                return $class;
            }
        }

        return null;
    }
}