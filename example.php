<?php
/*
Whois.php        PHP classes to conduct whois queries

Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic

Maintained by David Saez (david@ols.es)

For the most recent version of this package visit:

http://phpwhois.sourceforge.net

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

<?
if(isSet($query))
	{
	include_once('whois.main.php');
	
	$whois = new Whois();
	$result = $whois->Lookup($query);
	echo "<b>Results for  $query :</b><p>";

	if($output=="object") {
		include_once('whois.utils.php');
		$utils = new utils;
		$utils->showObject($result);
	}
	else {
		if(!empty($result['rawdata'])) {
			echo implode($result["rawdata"],"\n");
			}
        else {
			echo "<br>No Match";
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
<form method="post" action="<?echo $SCRIPT_NAME; ?>">
<table>
<tr><td colspan=2><b>Enter any domain name, ip address or AS handle you would like to query whois for<b></td></tr>
<tr><td align=center colspan=2><input name="query">
<input type="submit" value="Whois"></td></tr>
<tr><td colspan=2>
<input type="radio" name="output" value="normal" checked> Show me regular output
</td></tr>
<tr><td>
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
