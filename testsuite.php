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

// Read domain list to test
$cliHelper = new CliHelper();
$domains   = $cliHelper->loadDomainList('./test.txt');

// Load previous results

$fp = @fopen('testsuite.txt', 'rt');

if (!$fp) {
    $results = [];
} else {
    $results = unserialize(fgets($fp));
    fclose($fp);
}

$isContinuousIntegration = !empty($argv[1]) && $argv[1] === '--ci';

// Specific test ?

if (!empty($argv[1]) && isset($domains[$argv[1]])) {
    $domains = [$domains[$argv[1]]];
}

// Test domains
$whois = new Whois();

stream_set_write_buffer(STDIN, 0);

foreach ($domains as $domain) {
    echo "\nTesting $domain ---------------------------------\n";
    $result = $whois->lookup($domain);

    unset($result['rawdata']);

    if (!isset($results[$domain])) {
        print_r($result);
        $res = $isContinuousIntegration || get_answer("Add result for $domain");

        if ($res) {
            // Add as it is
            unset($result['regrinfo']['disclaimer']);
            $results[$domain] = $result;
            save_results();
        }
    } else {
        // Compare with previous result
        unset($result['regrinfo']['disclaimer']);
        unset($results[$domain]['regrinfo']['disclaimer']);

        if (empty($result)) {
            echo "!! empty result\n";
        } else {
            $diff = array_diff_assoc_recursive($result, $results[$domain]);

            if (is_array($diff)) {
                print_r($diff);
                $res = !$isContinuousIntegration && get_answer("Accept differences for $domain");

                if ($res) {
                    // Add as it is
                    $results[$domain] = $result;
                    save_results();
                }
            } else {
                echo "Handler for domain $domain gives same results as before ...\n";
            }
        }
    }
}

save_results();

//--------------------------------------------------------------------------

function save_results()
{
    global $results;

    $fp = fopen('testsuite.txt', 'wt');
    fputs($fp, serialize($results));
    fclose($fp);
}

//--------------------------------------------------------------------------

function get_answer($question)
{
    echo "\n------ $question ? (y/n/a/c) ";

    while (true) {
        $res = trim(fgetc(STDIN));

        if ($res == 'a') {
            exit();
        }

        if ($res == 'c') {
            save_results();
            exit();
        }
        if ($res == 'y') {
            return true;
        }
        if ($res == 'n') {
            return false;
        }
    }
}

//--------------------------------------------------------------------------

function array_diff_assoc_recursive($array1, $array2)
{
    foreach ($array1 as $key => $value) {
        if (is_array($value)) {
            if (!is_array($array2[$key] ?? '')) {
                $difference[$key] = ['previous' => $array2[$key] ?? '', 'actual' => $value];
            } else {
                $new_diff = array_diff_assoc_recursive($value, $array2[$key]);

                if ($new_diff != false) {
                    $difference[$key] = $new_diff;
                }
            }
        } else if (!isset($array2[$key]) || $array2[$key] != $value) {
            $difference[$key] = ['previous' => $array2[$key] ?? '', 'actual' => $value];
        }
    }

    // Search missing items

    foreach ($array2 as $key => $value) {
        if (!isset($array1[$key])) {
            $difference[$key] = ['previous' => $value, 'actual' => '(missing)'];
        }
    }

    return !isset($difference) ? false : $difference;
}
