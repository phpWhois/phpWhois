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
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 */

namespace phpWhois;

/**
 * Utilities for parsing ip addresses
 */
class IpTools {
    /**
     * Check if ip address is valid
     * 
     * @param string $ip IP address for validation
     * @return boolean
     */
    public function validIp($ip) {

        if (empty($ip))
            return false;

        $long = ip2long($ip);

        if ($long == -1 || $long === false)
            return false;

        $reserved_ips = array(
            array('0.0.0.0', '2.255.255.255'),
            array('10.0.0.0', '10.255.255.255'),
            array('127.0.0.0', '127.255.255.255'),
            array('169.254.0.0', '169.254.255.255'),
            array('172.16.0.0', '172.31.255.255'),
            array('192.0.2.0', '192.0.2.255'),
            array('192.168.0.0', '192.168.255.255'),
            array('255.255.255.0', '255.255.255.255')
        );

        foreach ($reserved_ips as $r) {
            $min = ip2long($r[0]);
            $max = ip2long($r[1]);
            if (($long >= $min) && ($long <= $max))
                return false;
        }

        return true;
    }

    /**
     * Try to get real IP from client web request
     *
     * @return string
     */
    public function getClientIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && $this->validIp($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            foreach (explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']) as $ip)
                if ($this->validIp(trim($ip)))
                    return trim($ip);

        if (!empty($_SERVER['HTTP_X_FORWARDED']) && $this->validIp($_SERVER['HTTP_X_FORWARDED']))
            return $_SERVER['HTTP_X_FORWARDED'];

        if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && $this->validIp($_SERVER['HTTP_FORWARDED_FOR']))
            return $_SERVER['HTTP_FORWARDED_FOR'];

        if (!empty($_SERVER['HTTP_FORWARDED']) && $this->validIp($_SERVER['HTTP_FORWARDED']))
            return $_SERVER['HTTP_FORWARDED'];

        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Convert CIDR to net range
     *
     * @TODO provide example
     *
     * @param string $net
     * @return string
     */
    public function cidrConv($net) {
        $start = strtok($net, '/');
        $n = 3 - substr_count($net, '.');

        if ($n > 0) {
            for ($i = $n; $i > 0; $i--)
                $start.= '.0';
        }

        $bits1 = str_pad(decbin(ip2long($start)), 32, '0', 'STR_PAD_LEFT');
        $net = pow(2, (32 - substr(strstr($net, '/'), 1))) - 1;
        $bits2 = str_pad(decbin($net), 32, '0', 'STR_PAD_LEFT');
        $final = '';

        for ($i = 0; $i < 32; $i++) {
            if ($bits1[$i] == $bits2[$i])
                $final.= $bits1[$i];
            if ($bits1[$i] == 1 and $bits2[$i] == 0)
                $final.= $bits1[$i];
            if ($bits1[$i] == 0 and $bits2[$i] == 1)
                $final.= $bits2[$i];
        }

        return $start . " - " . long2ip(bindec($final));
    }
}
