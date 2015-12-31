<?php

namespace phpWhois\Parser;

use phpWhois\Response;

abstract class ParserAbstract {
    /**
     * @var string  Raw response from whois server
     */
    protected $raw;

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
    }

    public function setRaw($raw)
    {
        $this->raw = $raw;
    }

    abstract public function parse();
}