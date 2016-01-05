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
use phpWhois\Provider\WhoisServer;
use phpWhois\Query;
use phpWhois\Response;

abstract class HandlerAbstract
{
    /**
     * @var null|array Date format in php date() format
     */
    protected $dateFormat = null;

    protected $patternsExpires = [
        '/expir(e|y|es|ation)/i',
        '/renew(al)?/i',
        '/paid\-till/i',
        '/validity/i',
        '/billeduntil/i'
    ];

    protected $patternsRegistered = [
        '/creat(ed|ion)/i',
        '/regist(ered|ration)/i',
        '/commencement/i',
    ];

    protected $patternsUpdated = [
        '/update(d)?/i',
        '/modif(y|ied|ication)/i',
        '/changed/i',
    ];

    protected $patternsStatusRegistered = [
        '/status$/i' => '/^ok/i',
    ];

    /**
     * @var ProviderAbstract Whois information provider
     */
    protected $provider;

    /**
     * @var Query
     */
    protected $query;

    /**
     * @var Server address for the provider
     */
    protected $server;

    /**
     * @var string  Raw response from whois server
     */
    protected $raw;

    /**
     * @var array   Array of response lines split by newlines
     */
    protected $lines;

    /**
     * @var array   Parsed data
     */
    protected $parsed;
    /**
     * Handler constructor
     *
     * Each handler must inherit this method and set provider
     *
     * TODO: Child constructor doesn't work well when server is set as an instance var. See Jp handler
     *
     * @param Query $query    Query for whois server
     * @param string $server    Whois server address
     */
    public function __construct(Query $query, $server = null)
    {
        $this->setQuery($query);

        // Default provider is WhoisServer
        $this->setProvider(new WhoisServer($query));

        if (is_null($server)) {
            $server = $this->getServer();
        }

        $this->setServer($server);
    }

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
     * Set provider server address
     *
     * @param string $server
     *
     * @return $this
     */
    public function setServer($server)
    {
        $this->getProvider()->setServer($server);

        return $this;
    }

    /**
     * Get provider server address
     */
    public function getServer()
    {
        return $this->getProvider()->getServer();
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
     * Set raw response from the whois server
     *
     * @param $raw
     *
     * @return $this
     */
    public function setRaw($raw)
    {
        $this->raw = $raw;

        return $this;
    }

    /**
     * Get raw response from the whois server
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * Set response split into lines by newline
     *
     * @param array $lines
     *
     * @return $this
     */
    protected function setLines(array $lines)
    {
        $this->lines = $lines;

        return $this;
    }

    /**
     * Get response split by newline
     *
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Set array with parsed data
     *
     * @param array $parsed
     *
     * @return $this
     */
    protected function setParsed(array $parsed)
    {
        $this->parsed = $parsed;

        return $this;
    }

    /**
     * Get array with parsed data
     *
     * @return array
     */
    public function getParsed()
    {
        return $this->parsed;
    }

    /**
     * Check if handler has all the necessary data assigned
     *
     * @return bool
     */
    public function hasData()
    {
        return $this->getQuery()->hasData()
                && !is_null($this->getProvider());
    }

    /**
     * Split raw data response into array by newline
     *
     * @param $raw  Raw response from whois server
     *
     * @return array
     */
    public function splitLines($raw = null)
    {
        if (is_null($raw)) {
            $raw = $this->getRaw();
        }

        // Line ending could be \r\n, \r, \n
        $lines = preg_split('/(\r\n|[\r\n])/', $raw);
        return $lines;
    }

    public function splitRow($row, $ignorePrefix = '/^[%]/i', $splitBy = '/(:)/i')
    {
        $result = false;

        // If ignorePrefix is not empty and row matches it - return false
        if (!empty($ignorePrefix) && preg_match($ignorePrefix, $row)) {
            $result = false;
            return $result;
        }

        $row = trim($row);
        $parts = preg_split($splitBy, $row, 2);
        if (count($parts) == 2) {
            $parts[1] = trim($parts[1]);
        }
        $result = $parts;

        return $result;
    }

    /**
     * Extract unix timestamp from the defined string
     *
     * @param string    $date    Date
     *
     * @return int|false  Unix timestamp
     */
    protected function parseDate($date)
    {
        $result = false;
        if ($this->dateFormat == null) {
            $result = strtotime($date);
        } elseif (count($this->dateFormat)) {
            foreach ($this->dateFormat as $format) {
                if ($dateTime = \DateTime::createFromFormat($format, $date)) {
                    $result = $dateTime->format('U');
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Try to extract the date from the given key and value
     *
     * @param string    $row    Line from raw whois response
     * @param array     $patterns       Array with patterns for matching the $key
     * @param array     $antiPatterns   Array with patterns which $key must not match
     *
     * @return false|int    Unix timestamp
     */
    protected function extractDate($row, array $patterns, array $antiPatterns = [])
    {
        $result = false;

        foreach ($antiPatterns as $ap) {
            if (preg_match($ap, $row)) {
                $result = false;
                return $result;
            }
        }

        $parts = $this->splitRow($row);
        if (count($parts) == 2) {
            $key = $parts[0];
            $value = $parts[1];
        } else {
            return false;
        }

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $key) && $time = $this->parseDate($value)) {
                $result = $time;
                break;
            }
        }

        return $result;
    }

    /**
     * Try to extract some useful info from the lines
     *
     * @param array    $rows   Rows in key => value format
     *
     * @return array
     */
    protected function extractDates(array $rows)
    {
        $dates = ['expires' => false, 'registered' => false, 'updated' => false];

        foreach ($rows as $row) {
            // Expiration date
            if (!$dates['expires']) {
                $dates['expires'] = ($this->extractDate($row, $this->patternsExpires)) ?: $dates['expires'];
            }
            // Registration date
            if (!$dates['registered']) {
                $dates['registered'] = ($this->extractDate($row, $this->patternsRegistered)) ?: $dates['registered'];
            }
            // Updated date
            if (!$dates['updated']) {
                $dates['updated'] = ($this->extractDate($row, $this->patternsUpdated, ['/>>>/i'])) ?: $dates['updated'];
            }
        }
        return $dates;
    }

    /**
     * Try to parse response into key => value array
     * WARNING: This is very dirty solution, since multiple keys will override each other
     *
     * @param array $rows
     * @return array
     */
    protected function extractKeyValue(array $rows)
    {
        $keyValue = [];
        foreach ($rows as $row) {
            $parts = $this->splitRow($row);
            if (count($parts) == 2) {
                $keyValue[$parts[0]] = $parts[1];
            }
        }
        return $keyValue;
    }

    /**
     * Perform parsing and set necessary properties
     *
     * @return array
     */
    protected function parse()
    {
        $lines = $this->splitLines();
        $this->setLines($lines);

        /**
         * TODO: Split into blocks first. Useful info cannot be extracted from the blocks in most cases
         */
        $parsed['dates'] = $this->extractDates($lines);
        $parsed['keyValue'] = $this->extractKeyValue($lines);

        $this->setParsed($parsed);

        return $this->getParsed();
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
        $this->setRaw($raw);

        // Set Response raw fields
        $response = new Response($this->getQuery());
        $response->setRaw($raw);

        $parsed = $this->parse();

        $response->setParsed($parsed);

        return $response;
    }
}