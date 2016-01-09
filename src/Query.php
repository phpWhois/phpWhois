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

use TrueBV\Punycode;

final class Query
{

    const QTYPE_UNKNOWN = -1;
    const QTYPE_DOMAIN  = 1;
    const QTYPE_IPV4    = 2;
    const QTYPE_IPV6    = 3;
    const QTYPE_AS      = 4;

    /**
     * @var int Query type (see constants)
     */
    private $type = self::QTYPE_UNKNOWN;

    /**
     * @var string  Original address received
     */
    private $addressOrig;

    /**
     * @var string  Address optimized for querying the whois server
     */
    private $address;

    /**
     * @var string[]    Additional params to apply to address when querying whois server
     */
    private $params = [];

    /**
     * Query constructor.
     *
     * @param   null|string  $address
     * @param   string[]    Array of params for whois server
     */
    public function __construct($address = null, array $params = [])
    {
        if (!is_null($address)) {
            $this->setAddress($address);
        }

        foreach ($params as $param) {
            $this->addParam($param);
        }
    }

    /**
     * Set address, make necessary checks and transformations
     *
     * @api
     *
     * @param   string  $address
     *
     * @return  $this
     *
     * @throws  \InvalidArgumentException    if address is not recognized
     */
    public function setAddress($address)
    {
        $type = $this->guessType($address);

        if ($type == self::QTYPE_UNKNOWN) {
            throw new \InvalidArgumentException('Address is not recognized, can\'t find whois server');
        } else {
            $this->setType($type);
        }

        $this->setAddressOrig($address);

        $this->address = $this->optimizeAddress($address);

        return $this;
    }

    /**
     * @return string   Address, optimized for querying whois server
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set original unoptimized address
     *
     * @param   string  $address
     *
     * @return $this
     */
    private function setAddressOrig($address)
    {
        $this->addressOrig = $address;

        return $this;
    }

    /**
     * Get original unoptimized address
     *
     * @return string   Original unoptimized address
     */
    public function getAddressOrig()
    {
        return $this->addressOrig;
    }

    /**
     * Check if class instance has valid address set
     *
     * @return bool
     */
    public function hasData()
    {
        return !is_null($this->getAddress());
    }

    /**
     * Set query type (See constants)
     *
     * @param int    $type
     *
     * @return $this
     */
    private function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get params array
     *
     * @return string[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Add param to query
     *
     * @param string    $param
     *
     * @return $this
     */
    public function addParam($param)
    {
        $this->params[] = strval($param);

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return ($this->type) ?: self::QTYPE_UNKNOWN;
    }

    /**
     * Find the type of a given address and make some optimizations like removing www.
     *
     * @api
     *
     * @param   string  $address
     *
     * @return  string  optimized address
     */
    public function optimizeAddress($address)
    {
        $type = $this->guessType($address);
        if ($type == self::QTYPE_DOMAIN) {

            $address = (new Punycode())->encode($address);

            $address_nowww = preg_replace('/^www./i', '', $address);
            if ((new QueryUtils())->validDomain($address_nowww)) {
                $address = $address_nowww;
            }
        }
        return $address;
    }

    /**
     * Guess address type
     *
     * @param   string  $address
     *
     * @return  int Query type
     */
    public function guessType($address)
    {
        $q = new QueryUtils();

        if ($q->validIp($address, 'ipv4', false)) {
            return ($q->validIp($address, 'ipv4')) ? self::QTYPE_IPV4 : self::QTYPE_UNKNOWN;
        } elseif ($q->validIp($address, 'ipv6', false)) {
            return ($q->validIp($address, 'ipv6')) ? self::QTYPE_IPV6 : self::QTYPE_UNKNOWN;
        } elseif ($q->validDomain($address)) {
            return self::QTYPE_DOMAIN;
        // TODO: replace with AS validator
        } elseif ($address && is_string($address) && strpos($address, '.') === false) {
            return self::QTYPE_AS;
        } else {
            return self::QTYPE_UNKNOWN;
        }
    }
}