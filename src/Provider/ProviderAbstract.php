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

namespace phpWhois\Provider;

use phpWhois\Query;

/**
 * Abstract class Provider - defines the algorithms for communicating with various whois servers
 */
abstract class ProviderAbstract {

    /**
     * @var string  Whois server to query
     */
    protected $server = '';
    /**
     * @var int Whois server port
     */
    protected $port;

    /**
     * @var Query
     */
    protected $query;

    /**
     * @param Query $query
     * @return mixed
     */
    abstract public function lookup(Query $query);

    /**
     * @param int $port
     * @return void
     */
    abstract public function setPort($port = 0);

    /**
     * @param Query     $query
     * @param string    $server
     */
    public function __construct(Query $query, $server)
    {
        $this->setQuery($query);
        $this->setServer($server);
    }

    public function setServer($server)
    {
        $parts = explode(':', $server);
        $this->server = $parts[0];
        if (count($parts) == 2) {
            $this->setPort($parts[1]);
        } else {
            $this->setPort();
        }
    }

    /**
     * @param Query $query
     */
    protected function setQuery(Query $query)
    {
        $this->query = $query;
    }
}