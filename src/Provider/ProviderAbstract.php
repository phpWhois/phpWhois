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

namespace phpWhois\Provider;

use phpWhois\Query;
use phpWhois\Response;

/**
 * Abstract class Provider - defines the algorithms for communicating with various whois servers
 */
abstract class ProviderAbstract {

    /**
     * @var string  Whois server to query
     */
    protected $server;

    /**
     * @var int Whois server port
     */
    protected $port;

    /**
     * @var int Timeout for connecting to the server
     */
    protected $timeout = 10;

    /**
     * @var int Number of retries to connect to the server. 0 - connect once (no retries)
     */
    protected $retry = 0;

    /**
     * @var int Number of seconds to sleep before retry
     */
    protected $sleep = 1;

    /**
     * @var int Connection error number
     */
    protected $connectionErrNo;

    /**
     * @var string  Connection error string
     */
    protected $connectionErrStr;

    /**
     * @var resource    Connection pointer
     */
    protected $connectionPointer;

    /**
     * @var Query
     */
    protected $query;

    /**
     * @var string  This is a raw query which will be sent to the Whois server (e.g. add "\r\n" to domain)
     */
    protected $rawQuery;

    /**
     * Connect to the defined server
     *
     * @return $this
     *
     * @throws \InvalidArgumentException    if server is not specified
     */
    abstract protected function connect();

    /**
     * Perform a request
     *
     * Perform a request to the defined whois server through the established connection
     * and return raw response
     *
     * @return string
     */
    abstract protected function performRequest();

    /**
     * @param Query     $query
     * @param string|null    $server
     */
    public function __construct(Query $query, $server = null)
    {
        $this->setQuery($query);
        $this->setServer($server);
    }

    /**
     * Set server and parse it if it is in a host:port format
     *
     * @param string    $server
     *
     * @return $this
     */
    public function setServer($server)
    {
        /**
         * TODO: Check if server is not empty
         */
        $parts = explode(':', $server);
        $this->server = $parts[0];

        if (count($parts) == 2) {
            $this->setPort(intval($parts[1]));
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set whois server port number
     *
     * @param int $port
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setPort($port)
    {
        if (!is_int($port)) {
            throw new \InvalidArgumentException('Port number must be an integer');
        }
        $this->port = $port;

        return $this;
    }

    /**
     * Get whois server port number
     *
     * @return int|null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set connection timeout
     *
     * @param int $timeout
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setTimeout($timeout = 10)
    {
        if (!is_int($timeout)) {
            throw new \InvalidArgumentException("Timeout must be integer number of seconds");
        }
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Get connection timeout
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Set number of connection retries.
     *
     * @param int $retry    Number of retries. 0 - connect once (no retries)
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setRetry($retry = 0)
    {
        if (!is_int($retry)) {
            throw new \InvalidArgumentException("Number of retries must be integer value");
        }
        $this->retry = $retry;

        return $this;
    }

    /**
     * Get number of retries
     *
     * @return int  Number of retries. 0 - connect once (no retries)
     */
    public function getRetry()
    {
        return $this->retry;
    }

    /**
     * Set number of seconds to sleep before next retry
     *
     * @param int $sleep
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setSleep($sleep = 0)
    {
        if (!is_int($sleep)) {
            throw new \InvalidArgumentException("Number of seconds to sleep must be integer");
        }

        $this->sleep = $sleep;

        return $this;
    }

    /**
     * Get number of seconds to sleep before next retry
     *
     * @return int
     */
    public function getSleep()
    {
        return $this->sleep;
    }

    /**
     * Set connection error number
     *
     * @param int   $errno
     *
     * @return $this
     */
    protected function setConnectionErrNo($errno)
    {
        $this->connectionErrNo = $errno;

        return $this;
    }

    /**
     * Get connection error number
     *
     * @return int|null
     */
    public function getConnectionErrNo()
    {
        return $this->connectionErrNo;
    }

    /**
     * Set connection error message as a string
     *
     * @param string    $errstr
     *
     * @return $this
     */
    protected function setConnectionErrStr($errstr)
    {
        $this->connectionErrStr = $errstr;

        return $this;
    }

    /**
     * Get connection error message as a string
     *
     * @return string|null
     */
    public function getConnectionErrStr()
    {
        return $this->connectionErrStr;
    }

    /**
     * Set connection pointer
     *
     * @param resource $pointer
     * @return $this
     *
     * @throws \InvalidArgumentException    if pointer is not valid
     */
    protected function setConnectionPointer($pointer)
    {
        if ($pointer) {
            $this->connectionPointer = $pointer;
        } else {
            throw new \InvalidArgumentException('Valid connection pointer (resource) must be provided');
        }

        return $this;
    }

    /**
     * Get connection pointer
     *
     * @return resource|null
     */
    protected function getConnectionPointer()
    {
        return $this->connectionPointer;
    }

    /**
     * Check if connection is established with the whois server
     *
     * @return bool
     */
    protected function isConnected()
    {
        if ($this->getConnectionPointer()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set query
     *
     * @param Query $query
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setQuery(Query $query)
    {
        if ($query->hasData()) {
            $this->query = $query;
        } else {
            throw new \InvalidArgumentException('Cannot assign an empty query');
        }

        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set raw query for querying whois server
     *
     * @param string $rawQuery
     *
     * @return $this
     */
    public function setRawQuery($rawQuery)
    {
        $this->rawQuery = $rawQuery;

        return $this;
    }

    /**
     * @return string
     */
    public function getRawQuery()
    {
        return $this->rawQuery;
    }

    /**
     * Check if instance has set query, server and server port
     *
     * @return bool
     */
    public function hasData() {
        return $this->getQuery()->hasData()
            && !empty($this->getServer())
            && !empty($this->getPort());
    }

    /**
     * Perform a lookup and return Response object
     *
     * @return string
     */
    public function lookup()
    {
        $raw = $this
                ->connect()
                ->performRequest();

        return $raw;
    }
}