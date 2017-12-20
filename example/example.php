<?php
/**
 * phpWhois Example
 * 
 * This class supposed to be instantiated for using the phpWhois library
 * 
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

header('Content-Type: text/html; charset=UTF-8');

if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require_once __DIR__.'/../vendor/autoload.php';
}

use phpWhois\Whois;
use phpWhois\Utils;
$whois = new Whois();

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    if (!empty($_GET['output']))
        $output = $_GET['output'];
    else
        $output = '';

    // Set to true if you want to allow proxy requests
    $allowproxy = false;

    // get faster but less acurate results
    $whois->deepWhois = empty($_GET['fast']);

    // To use special whois servers (see README)
    //$whois->useServer('uk','whois.nic.uk:1043?{hname} {ip} {query}');
    //$whois->useServer('au','whois-check.ausregistry.net.au');

    $result = $whois->lookup($query);

    $winfo = '';

    switch ($output) {
        case 'object':
            if ($whois->query['status'] < 0) {
                $winfo = implode($whois->query['errstr'], "\n<br></br>");
            } else {
                $utils = new Utils;
                $winfo = $utils->showObject($result);
            }
            break;

        case 'nice':
            if (!empty($result['rawdata'])) {
                $utils = new Utils;
                $winfo = $utils->showHTML($result);
            } else {
                if (isset($whois->query['errstr']))
                    $winfo = implode($whois->query['errstr'], "\n<br></br>");
                else
                    $winfo = 'Unexpected error';
            }
            break;

        case 'proxy':
            if ($allowproxy)
                exit(serialize($result));

        default:
            if (!empty($result['rawdata'])) {
                $winfo .= '<pre>' . implode($result['rawdata'], "\n") . '</pre>';
            } else {
                $winfo = implode($whois->query['errstr'], "\n<br></br>");
            }
    }

}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<title>whois.php -base classes to do whois queries with php</title>
<style type="text/css">
</style>
</head>

<body>
<center>

<h1>phpWhois - base class to do whois queries with php</h1>

<p>
&copy; 1999 - 2011 <a href="http://www.easydns.com/">easyDNS
Technologies Inc.</a> &amp; <a href="http://mark.jeftovic.net/">Mark Jeftovic</a><br/>
Now maintained and hosted by David Saez at <a href="http://www.ols.es">OLS 20000</a><br/>
Placed under the GPL. See the LICENSE file in the distribution.
</p>

<table>
<tr><td bgcolor="#55aaff">
<form method="get" action="">

<table>
<tr><td colspan="3" align="center">
<b>Enter any domain name, ip address or AS handle you would like to query whois for</b>
<br/><br/>
<input name="query" /> <input type="submit" value="Whois" /><br/>
</td></tr>

<tr>
<td>
<input type="radio" name="output" value="normal" /> Show me regular output<br/>
<input type="radio" name="output" value="nice" checked="checked" /> Show me HTMLized output<br/>
<input type="radio" name="output" value="object" /> Show me the returned PHP object
</td>

<td align="left" valign="top">
<input type="checkbox" name="fast" value="1" /> Fast lookup
</td>

<td align="right" valign="bottom">
<a href="http://www.phpwhois.org" title="phpWhois web page">
<img border="0" src="whois.icon.png" alt="phpWhois web page" /></a>
</td>
</tr>

</table>
</form>
</td></tr>
</table>
</center>

<?php if (!empty($query)):?>
<?php
// @TODO
//XSS attack here ?>
<p><b>Results for <?php echo $query?> :</b></p>
<blockquote>
<?php echo $winfo ?>
</blockquote>
<!--/results-->
<?php endif ?>

</body>
</html>
