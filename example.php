<?php
/*
Whois.php        PHP classes to conduct whois queries

Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic

Maintained by David Saez (david@ols.es)

For the most recent version of this package visit:

http://www.phpwhois.org

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>whois.php -base classes to do whois queries with php</title>
</head>
<body bgcolor="white">

<?
if(isSet($_GET['query']))
	{
	$query = $_GET['query'];
	
	if (!empty($_GET['output']))
		$output = $_GET['output'];
	else
		$output = '';
		
	include_once('whois.main.php');
	include_once('whois.utils.php');
	
	$whois = new Whois();
	
 	// uncomment the following line to get faster but less acurate results
 	// $whois->deep_whois = false;
 	
 	// To use special whois servers (see README)	
	//$whois->UseServer('uk','whois.nic.uk:1043?{hname} {ip} {query}');
	//$whois->UseServer('au','whois-check.ausregistry.net.au');
	
	// uncomment the following line to add support for non ICANN tld's
	// $whois->non_icann = true;
	
	$result = $whois->Lookup($query);
	echo "<blockquote><b>Results for $query :</b><br></br>";

	switch ($output)
		{
		case 'object':
			if ($whois->Query['status'] < 0)
				{
				echo implode($whois->Query['errstr'],"\n<br></br>");
				}
			else
				{
				$utils = new utils;
				echo $utils->showObject($result);
				}
			break;
			
		case 'nice':			
			if (!empty($result['rawdata'])) {				
				$utils = new utils;
				echo $utils->showHTML($result);
				}
			else {
				echo implode($whois->Query['errstr'],"\n<br></br>");
				}       
			break;
						
		default:
			if(!empty($result['rawdata'])) {
				echo '<pre>'.implode($result['rawdata'],"\n").'</pre>';
				}
			else {
				echo implode($whois->Query['errstr'],"\n<br></br>");
				}       
	}
	echo '</blockquote>';
}
?>

<center>
<table>
<tr><td bgcolor="#55aaff">
<form method="get" action="<?echo  $_SERVER['PHP_SELF']; ?>">

<table>
<tr><td colspan=2>
<center>
<b>Enter any domain name, ip address or AS handle you would like to query whois for</b>
<br></br><br></br>
<input name="query"></input> <input type="submit" value="Whois"></input>
</center>
</td></tr>

<tr><td>
<input type="radio" name="output" value="normal"></input> Show me regular output
<br></br>
<input type="radio" name="output" value="nice" checked></input> Show me HTMLized output
<br></br>
<input type="radio" name="output" value="object"></input> Show me the returned PHP object
</td>

<td align=right valign=bottom>
<a href="http://phpwhois.sourceforge.net">
<img border=0 src="whois.icon.png" alt=""></img><br></br>
</a>
</td>
</tr>

</table>
</form>
</td></tr>
</table>
</center>
</body>
</html>