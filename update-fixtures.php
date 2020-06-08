#!/usr/local/bin/php -n
<?php
/**
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
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
 * @link      http://phpwhois.pw
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 */

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use phpWhois\Cli\CliHelper;
use phpWhois\Whois;

$fixturePath = 'tests/fixtures/';

// Read domain list to test
$cliHelper = new CliHelper();
$domains   = $cliHelper->loadDomainList('./test.txt');

// Specific test ?
if (!empty($argv[1]) && isset($domains[$argv[1]])) {
    $domains = [$domains[$argv[1]]];
}

// Test domains
$whois = new Whois();

stream_set_write_buffer(STDIN, 0);

$domainCount = count($domains);
$ndx         = 1;
foreach ($domains as $domain) {
    try {
        echo "[$ndx/$domainCount] Creating fixture for `$domain`\n";
        $result = $whois->whois($domain);

        $safeDomain = makePathSafe($domain);
        file_put_contents("{$fixturePath}/{$safeDomain}.txt", $result);
        $ndx += 1;
    } catch (Exception $exception) {
        echo "  Exception: {$error->getMessage()}\n";
    } catch (Error $error) {
        echo "  Err: {$error->getMessage()}\n";
    }
}

function makePathSafe(string $filename): string
{
    return str_replace(":", '-', $filename);
}

// sha1("this-domain-is-not-registered");