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

class WhoisServer extends ProviderAbstract {

    /**
     * @var int Stream reading timeout
     */
    private $streamTimeout = 7;

    /**
     * @var int Whois server port
     */
    protected $port = 43;

    /**
     * Set stream timeout
     *
     * @param int $streamTimeout
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    private function setStreamTimeout($streamTimeout = 7)
    {
        if (!is_int($streamTimeout)) {
            throw new \InvalidArgumentException("Stream timeout must be integer number of seconds");
        }
        $this->streamTimeout = $streamTimeout;

        return $this;
    }

    /**
     * Get stream timeout
     *
     * @return int
     */
    private function getStreamTimeout()
    {
        return $this->streamTimeout;
    }

    /**
     * @inheritdoc
     */
    protected function connect()
    {
        $server = $this->getServer();
        $port = $this->getPort();

        if (!$server || !$port) {
            throw new \InvalidArgumentException('Whois server is not defined. Cannot connect');
        }

        $attempt = 0;
        while ($attempt <= $this->getRetry()) {

            // Sleep before retrying next attempt
            if ($attempt > 0) {
                sleep($this->getSleep());
            }

            $fp = @fsockopen('tcp://'.$server, $port, $errno, $errstr, $this->getTimeout());

            $this
                ->setConnectionErrNo($errno)
                ->setConnectionErrStr($errstr);

            if (!$fp) {
                $attempt++;
            } else {
                stream_set_timeout($fp, $this->getStreamTimeout());
                stream_set_blocking($fp, true);
                $this->setConnectionPointer($fp);
                break; // Connection established, exit loop
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function performRequest()
    {
        $raw = null;
        if ($this->isConnected()) {
            $fp = $this->getConnectionPointer();

            $query = $this->getQuery();

            $request = $query->getAddress();
            $request .= implode($query->getParams());
            $request .= "\r\n";

            fwrite($fp, $request);

            $r = [$fp];
            $w = null;
            $e = null;
            stream_select($r, $w, $e, $this->getStreamTimeout());

            $raw = stream_get_contents($fp);

            fclose($fp);
        }

        return $raw;
    }
}