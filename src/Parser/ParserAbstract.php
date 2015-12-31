<?php

namespace phpWhois\Parser;

use phpWhois\Response;

abstract class ParserAbstract {
    /**
     * @var string  Raw response from whois server
     */
    protected $raw;

    /**
     * @var array   Response split by newline
     */
    protected $lines;

    /**
     * @var array   Parsed
     */
    protected $parsed;

    /**
     * Parse given array of strings
     *
     * @param array $lines
     *
     * @return array
     */
    abstract protected function parseLines(array $lines);

    /**
     * ParserAbstract constructor
     *
     * @param Response|string|null $response    Response from whois server
     */
    public function __construct($response = null)
    {
        if ($response instanceof Response) {
            $response = $response->getRaw();
        }
        $this->setRaw($response);

        $this->parse();
    }

    /**
     * Set raw response text
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
     * Get raw response text
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
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
        $lines = preg_split('/(\r\n|[\r\n])/', $raw);
        return $lines;
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
     * Perform parsing and set necessary properties
     *
     * @return array
     */
    public function parse()
    {

        $lines = $this->splitLines();
        $this->setLines($lines);

        $parsed = $this->parseLines($this->getLines());
        $this->setParsed($parsed);

        return $this->getParsed();
    }
}