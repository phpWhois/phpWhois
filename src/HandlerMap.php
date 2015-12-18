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

use phpWhois\Handler;

class HandlerMap
{
    /**
     * @var array   Mappings from domain name to handler class
     */
    protected static $map = [
        /**
         * TODO: Some domains should be bound to registrars handlers rather than to specific domains handlers
         */
        'ru' => Handler\Ru::class,
//        TODO: su is utf8 as well
//        TODO: ru.com is a different registrar (Available with centralnic)
//        '/^(?:[a-z0-9\-]+?\.){1,2}ru$/i' => Handler\Registrar\NicRu::class,
//        '/^(?:[a-z0-9\-]+?\.){1,2}su$/i' => Handler\Registrar\NicRu::class,
//        '/^(?:[a-z0-9\-]+?\.){1}ru\.net$/i' => Handler\Registrar\NicRu::class,
//        '/^(?:[a-z0-9\-]+?\.){1}\.moscow$/i' => Handler\Registrar\NicRu::class,
    ];

    protected static function getMap()
    {
        return self::$map;
    }

    /**
     * Check if domain handler exists in mapping table
     *
     * @param string $domain
     * @return bool
     */
    public static function mappingExists($domain = '')
    {
        return $domain && is_string($domain) && array_key_exists($domain, self::getMap());
    }

    /**
     * @param string $domain
     * @return Handler\HandlerAbstract|false
     */
    public static function getHandler($domain = '')
    {
        if (self::mappingExists($domain)) {
            $map = self::getMap();
            return new $map[$domain];
        }
        /**
         * TODO: Try whois.nic.$tld, etc
         */
        return false;
    }
}