<?php
/*
Whois.php        PHP classes to conduct whois queries

Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic

Maintained by David Saez

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

class utils extends Whois {

	// showObject() and debugObject()
	// - debug code to show an object or array

	function showObject(&$obj)
		{
		$r = $this->debugObject($obj);
		return "<pre>$r</pre>\n";
		}

	function debugObject($obj,$indent=0)
		{
		if (is_Array($obj))
			{
			$return = '';
			foreach($obj as $k => $v)
				{
				$return .= str_repeat('&nbsp;',$indent);
				$return .= $k."->$v\n";
				$return .= $this->debugObject($v,$indent+1);
			}
			return $return;
		}
	}

	function ns_rr_defined($query) {
		return checkdnsrr($query,'NS');
	}

	// get nice HTML output

	function showHTML($result, $link_myself=true, $params='query=$0&amp;output=nice') {

		// adds links fort HTML output

		$email_regex = "/([-_\w\.]+)(@)([-_\w\.]+)\b/i";
		$html_regex = "/(?:^|\b)((((http|https|ftp):\/\/)|(www\.))([\w\.]+)([,:%#&\/?~=\w+\.-]+))(?:\b|$)/is";
		$ip_regex = "/\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/i";

		$out = '';
		$lempty = true;

		foreach($result['rawdata'] as $line)
			{
			$line = trim($line);

			if ($line == '')
				{
				if ($lempty) continue;
				else $lempty = true;
				}
			else
				$lempty = false;

			$out .= $line."\n";
			}

		if ($lempty) $out = trim($out);

		$out = strip_tags($out);
		$out = preg_replace ($email_regex, '<a href="mailto:$0">$0</a>', $out);
		$out = preg_replace_callback ($html_regex, 'href_replace', $out);

		if ($link_myself)
			{
			if ($params[0] == '/')
				$link = $params;
			else
				$link = $_SERVER['PHP_SELF'].'?'.$params;

			$out = preg_replace ($ip_regex, '<a href="'.$link.'">$0</a>', $out);

			if (isset($result['regrinfo']['domain']['nserver']))
				{
				$nserver = $result['regrinfo']['domain']['nserver'];
				}
			else
				$nserver = false;

			if (isset($result['regrinfo']['network']['nserver']))
				{
				$nserver = $result['regrinfo']['network']['nserver'];
				}

			if (is_array($nserver))
				{
				reset($nserver); 
				while (list($host, $ip) = each($nserver))
					{
					$url = '<a href="'. str_replace('$0',$ip,$link)."\">$host</a>";
					$out = str_replace($host, $url, $out);
					$out = str_replace(strtoupper($host), $url, $out);
					}
				}
			}

		// Add bold field names

		$out = preg_replace ("/(?m)^([-\s\.&;'\w\t\(\)\/]+:\s*)/", '<b>$1</b>', $out);

		// Add italics for disclaimer

		$out = preg_replace ("/(?m)^(%.*)/", '<i>$0</i>', $out);

		return str_replace("\n","<br/>\n",$out);
	}
}

function href_replace($matches)
{
if (substr($matches[0],0,4)=='www.')
	{
	$web=$matches[0];
	$url='http://'.$web;
	}
else
	{
	$web=$matches[0];
	$url=$web;
	}

return '<a href="'.$url.'" target="_blank">'.$web.'</a>';
}
?>
