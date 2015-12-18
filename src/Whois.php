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


/**
 * phpWhois main class
 *
 * This class supposed to be instantiated for using the phpWhois library
 */
class Whois
{
    /**
     * @var \phpWhois\Handler\HandlerAbstract   Handler for obtaining address whois information
     */
    protected $handler;

    /**
     * @var string  Address to lookup
     */
    protected $address;

    /**
     * @param string $address
     * @return $this
     * @throws \InvalidArgumentException    if address is empty
     */
    public function setAddress($address = '')
    {
        if ($address && is_string($address)) {
            $this->address = $address;
        } else {
            throw new \InvalidArgumentException('Address cannot be empty');
        }
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Handler\HandlerAbstract $handler  Handler for obtaining address whois information
     */
    public function setHandler(\phpWhois\Handler\HandlerAbstract $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param string $address
     * @return mixed
     * @throws \InvalidArgumentException    if address is empty
     */
    public function lookup($address = '')
    {
        if ($address) {
            $this->setAddress($address);
        }

        if (!($address = $this->getAddress())) {
            throw new \InvalidArgumentException('Address cannot be empty');
        }

        $handler = HandlerMap::getHandler($address);
        $this->setHandler($handler);
        return $this->handler->parse();
    }
}