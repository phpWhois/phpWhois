<?php

namespace phpWhois\Provider;

use phpWhois\Query;

abstract class ProviderAbstract {
    /**
     * @var string  Address to lookup
     */
    protected $address;

    protected $server = '';
    protected $port = 53;

    /**
     * @var string  Address to lookup. Use $this->address if missing
     * @return mixed
     */
    abstract function lookup($address);

    /**
     * @string $address Address to lookup
     * @return mixed
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    abstract protected function setQuery(Query $query);
}