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

namespace phpWhois\Handler;

use phpWhois\Provider\ProviderAbstract;
use phpWhois\Parser\ParserAbstract;
use phpWhois\Query;
use phpWhois\Response;

abstract class HandlerAbstract
{
    /**
     * @var ProviderAbstract Whois information provider
     */
    protected $provider;

    /**
     * @var Query
     */
    protected $query;

    /**
     * @var string  Whois server address
     */
    protected $server;

    /**
     * @var Parser  Parser instance
     */
    protected $parser;

    /**
     * Handler constructor
     *
     * Each handler must inherit this method and set provider
     *
     * @param Query $query    Query for whois server
     * @param string $server    Whois server address
     */
    public function __construct(Query $query, $server = null)
    {
        $this->setQuery($query);

        if (!is_null($server)) {
            $this->setServer($server);
        }
    }

    /**
     * TODO: Set certain parser here
     */

    /**
     * Set query
     *
     * @param Query $query
     *
     * @return $this
     */
    protected function setQuery(Query $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get query
     *
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set server address
     *
     * @param $server
     *
     * @return $this
     */
    public function setServer($server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server address
     *
     * @return string $server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set provider
     *
     * @param ProviderAbstract $provider
     *
     * @return $this
     */
    protected function setProvider(ProviderAbstract $provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return ProviderAbstract
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set parser
     *
     * @param ParserAbstract $parser
     *
     * @return $this
     */
    public function setParser(ParserAbstract $parser)
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * Get parser
     *
     * @return ParserAbstract
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * Check if handler has all the necessary data assigned
     *
     * @return bool
     */
    public function hasData()
    {
        return $this->getQuery()->hasData()
                && !is_null($this->getProvider())
                && !is_null($this->getParser());
    }

    /**
     * Perform a lookup of defined query
     *
     * @return Response
     *
     * @throws \InvalidArgumentException
     */
    public function lookup()
    {
        if (!$this->hasData()) {
            throw new \InvalidArgumentException('Handler doesn\'t have query or provider set');
        }

        // Get raw response from provider
        $raw = $this->getProvider()->lookup();

        // Set Response raw fields
        $response = new Response($this->getQuery());
        $response->setRaw($raw);

        /**
         * TODO: most probably pass response to parser for ability to properly parse all necessary fields
         */
        $this->getParser()->setRaw($response->getRaw());

        $parsed = $this->getParser()->parse();

        $response->setParsed($parsed);

        return $response;
    }
}