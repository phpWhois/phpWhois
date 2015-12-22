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
use phpWhois\Provider\ProviderAbstract;

/**
 * Response from WhoisServer
 */
class Response
{
    /**
     * @var Query
     */
    protected $query;

    /**
     * @var ProviderAbstract
     */
    protected $provider;

    /**
     * @var string Raw data received from whois server
     */
    protected $raw;

    /**
     * Response constructor.
     * @param Query $query
     */
    public function __construct(Query $query)
    {
        $this->setQuery($query);
    }

    /**
     * Set not parsed raw response from the whois server
     *
     * @var string  $raw;
     */
    public function setRaw($raw)
    {
        $this->raw = $raw;
    }

    /**
     * Get not parsed raw response from whois server
     *
     * @return string|null
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * Set query
     *
     * @param Query $query
     *
     * @return $this
     *
     * @throws \InvalidArgumentException    if query is empty
     */
    public function setQuery(Query $query)
    {
        if (!$query->hasData()){
            throw new \InvalidArgumentException('Cannot assign an empty query');
        }

        $this->query = $query;

        return $this;
    }

    /**
     * @param ProviderAbstract &$provider
     *
     * @return $this
     */
    public function setProvider(ProviderAbstract $provider)
    {
        $this->provider = $provider;

        return $this;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function getData()
    {
        $result = [
            'query' => [
                'address' => $this->query->getAddress(),
                'addressOrig' => $this->query->getAddressOrig(),
            ],
            'server' => [
                'name' => $this->provider->getServer(),
                'port' => $this->provider->getPort(),
                'errno' => $this->provider->getConnectionErrNo(),
                'errstr' => $this->provider->getConnectionErrStr(),
            ],
            'responseRaw' => $this->getRaw(),
        ];
        return $result;
    }

    public function getJson()
    {
        return json_encode($this->getData());
    }
}