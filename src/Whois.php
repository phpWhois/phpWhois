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
use phpWhois\Handler\Registrar\Generic as GenericHandler;

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

    /**
     * @var Response    Response from custom or IANA-suggested whois server
     */
    protected $response;

    /**
     * Whois constructor.
     *
     * @param null|string $address  Address to query
     */
    public function __construct($address = null)
    {
        $this->setQuery(new Query());

        if (!is_null($address)) {
            $this->setAddress($address);
        }
    }

    /**
     * Set query instance
     *
     * @param Query $query  Set query instance
     *
     * @return $this
     */
    protected function setQuery(Query $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get instance
     *
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set address
     *
     * @param   string  $address  Address
     *
     * @return $this
     *
     * @throws \InvalidArgumentException    When address is not recognized
     */
    public function setAddress($address)
    {
        $this->getQuery()->setAddress($address);

        return $this;
    }

    /**
     * Set Query handler
     *
     * @param null|HandlerAbstract $handler  Handler for querying whois server
     *
     * @return $this
     */
    public function setHandler(HandlerAbstract $handler = null)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * Get query handler
     *
     * @return null|HandlerAbstract
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Get response from IANA-specified whois server
     *
     * @param Response $response
     *
     * @return $this
     */
    protected function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Perform a lookup of address
     *
     * 1. Query IANA whois server first to obtain the default whois server.
     * 2. If IANA returned the default server address - query it
     * 3. If special handler is set - try to query it as well
     *
     * @param null|string $address
     *
     * @return Response Response from the whois server
     * @throws \InvalidArgumentException    if address is empty
     */
    public function lookup($address = null)
    {
        if (!is_null($address)) {
            $this->setAddress($address);
        }

        if (!$this->getQuery()->hasData()) {
            throw new \InvalidArgumentException('Address wasn\'t set, can\'t perform a query');
        }

        // If handler is not set yet, then try to find a custom handler
        if (!($this->getHandler() instanceof HandlerAbstract)) {
            $handler = DomainHandlerMap::findHandler($this->getQuery());
            $this->setHandler($handler);
        }

        // If handler isn't set or custom handler doesn't have server address defined - obtain server address from IANA
        if (!($this->getHandler() instanceof HandlerAbstract) || empty($this->getHandler()->getServer())) {
            $ianaHandler = new GenericHandler($this->getQuery(), 'whois.iana.org');
            $responseIana = $ianaHandler->lookup();

            $serverAddress = $responseIana->getByKey('whois');

            if (empty($serverAddress)) {
                throw new \InvalidArgumentException('Cannot find whois server. Consider creating custom handler with predefined server address');
            }

            if (!($this->getHandler() instanceof HandlerAbstract)) {
                $handler = new GenericHandler($this->query);
                $this->setHandler($handler);
            }

            $this->getHandler()->setServer($serverAddress);
        }

        $response = $this->getHandler()->lookup();
        $this->setResponse($response);

        return $this->getResponse();
    }
}