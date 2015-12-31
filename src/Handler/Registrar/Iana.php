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
use phpWhois\Query;

class Iana extends HandlerAbstract
{
    public function __construct(Query $query)
    {
        parent::__construct($query);
        $provider = new WhoisServer($query, 'whois.iana.org');
        $rawQuery = $provider->getRawQuery();
        //$provider->setRawQuery()
        $this->setProvider(new WhoisServer($query, 'whois.iana.org'));
    }
}