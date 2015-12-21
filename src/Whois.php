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

use phpWhois\Query;
use phpWhois\Handler\HandlerAbstract;

/**
 * phpWhois main class
 *
 * This class supposed to be instantiated for using the phpWhois library
 */
class Whois
{
    /**
     * @var HandlerAbstract   Handler for obtaining address whois information
     */
    protected $handler;

    /**
     * @var Query   Query object created from given domain name
     */
    protected $query;

    public function __construct($address = null)
    {
        $this->setQuery(new Query());

        if (!is_null($address)) {
            $this->setAddress($address);
        }
    }

    protected function setQuery(Query $query)
    {
        $this->query = $query;
    }

    /**
     * Set query
     *
     * @param   string  $address  Address
     *
     * @return $this
     *
     * @throws \InvalidArgumentException    When handler not found
     */
    public function setAddress($address)
    {
        $this->query->setAddress($address);

        // TODO: Allow people to use their own handlers
        $handler = DomainHandlerMap::findHandler($this->query);
        if ($handler === false) {
            throw new \InvalidArgumentException('Handler not found for this address. Giving up');
        }
        $this->setHandler($handler);

        return $this;
    }

    /**
     * @param HandlerAbstract $handler  Handler for querying whois server
     */
    public function setHandler(HandlerAbstract $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Perform a lookup of address
     *
     * @param null|string $address
     *
     * @return Response
     *
     * @throws \InvalidArgumentException    if address is empty
     */
    public function lookup($address = null)
    {
        if (!is_null($address)) {
            $this->setAddress($address);
        }

        if (!($this->handler instanceof HandlerAbstract) || !$this->handler->hasData()) {
            throw new \InvalidArgumentException('Domain handler wasn\'t set up correctly');
        }

        return $this->handler->lookup();
    }
}