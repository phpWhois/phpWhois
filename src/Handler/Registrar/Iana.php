<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 21.12.15
 * Time: 23:33
 */

namespace phpWhois\Handler\Registrar;

use phpWhois\Handler\HandlerAbstract;
use phpWhois\Provider\WhoisServer;
use phpWhois\Parser\ParserA;
use phpWhois\Query;

class Iana extends HandlerAbstract
{
    public function __construct(Query $query, $server)
    {
        parent::__construct($query, $server);

        $provider = new WhoisServer($this->getQuery(), $this->getServer());
        $this->setProvider($provider);

        $parser = new ParserA();
        $this->setParser($parser);
    }
}