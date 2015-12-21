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
 * Response from WhoisServer
 */
class Response
{
    /**
     * @var int Connection error number
     */
    protected $connectionErrNo;

    /**
     * @var string  Connection error string
     */
    protected $connectionErrStr;

    /**
     * @var string Raw data received from whois server
     */
    protected $rawData;

    public function __construct($raw = null)
    {
        if (!is_null($raw)) {
            $this->setRawData($raw);
        }
    }

    /**
     * Get not parsed raw response from the whois server
     *
     * @var string  $raw;
     */
    public function setRawData($raw)
    {
        $this->rawData = $raw;
    }

    /**
     * Get not parsed raw response from whois server
     *
     * @return string|null
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * Set connection error number
     *
     * @param int   $errno
     *
     * @return Response
     */
    public function setConnectionErrNo($errno)
    {
        $this->connectionErrNo = $errno;

        return $this;
    }

    /**
     * Get connection error number
     *
     * @return null|int
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
    public function setConnectionErrStr($errstr)
    {
        $this->connectionErrStr = $errstr;

        return $this;
    }

    /**
     * Get connection error message as a string
     *
     * @return null|string
     */
    public function getConnectionErrStr()
    {
        return $this->connectionErrStr;
    }
}