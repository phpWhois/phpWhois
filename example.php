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

<head>
<title>whois.php -base classes to do whois queries with php</title>
</head>
<body bgcolor="ffffff">
<blockquote>
<pre>
<blockquote>

<?php
if(isSet($_GET['query']))
	{
	$query = $_GET['query'];
	$output = $_GET['output'];
	
	include_once('whois.main.php');
	include_once('whois.utils.php');
	
	$whois = new Whois();
	$result = $whois->Lookup($query);
	echo "<b>Results for $query :</b><p>";

	switch ($output)
		{
		case 'object':
			if ($whois->Query['status'] < 0)
				{
				echo implode($whois->Query['errstr'],"\n<br>");
				}
			else
				{
				$utils = new utils;
				echo $utils->showObject($result);
				}
			break;
			
		case 'nice':			
			if(!empty($result['rawdata'])) {				
				$utils = new utils;
				echo $utils->showHTML($result);
				}
			else {
				echo implode($whois->Query['errstr'],"\n<br>");
				}       
			break;
						
		default:
			if(!empty($result['rawdata'])) {
				echo implode($result['rawdata'],"\n");
				}
			else {
				echo implode($whois->Query['errstr'],"\n<br>");
				}       
	}
}
?>

</blockquote>
</pre>
</blockquote>
<center>
<table>
<tr><td bgcolor="55aaff">
<form method="get" action="<?php echo  $_SERVER['PHP_SELF']; ?>">

<table>
<tr><td colspan=2>
<center>
<b>Enter any domain name, ip address or AS handle you would like to query whois for<b>
<br><br>
<input name="query"> <input type="submit" value="Whois">
</center>
</td></tr>

<tr><td>
<input type="radio" name="output" value="normal"> Show me regular output
<br>
<input type="radio" name="output" value="nice" checked> Show me HTMLized output
<br>
<input type="radio" name="output" value="object"> Show me the returned PHP object
</td>

<td align=right valign=bottom>
<a href="http://phpwhois.sourceforge.net">
<img border=0 src="whois.icon.png" alt=""><br>
</a>
</td>
</tr>

</table>
</form>
</td></tr>
</table>
</center>
