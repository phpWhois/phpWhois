#!/usr/local/bin/php -n
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

// Read domain list to test

$lines = file('./test.txt');
$domains = array();

foreach ($lines as $key => $line)
	{ 
	$pos = strpos($line,'/');
	
	if ($pos !== false) $line = substr($line,0,$pos);
	
	$line = trim($line);
	
	if ($line=='') continue;
	
	$parts = explode(' ',str_replace("\t",' ',$line));
	
	for ($i=1;$i<count($parts);$i++)	
		if ($parts[$i]!='')
			$domains[] = $parts[$i];
	}

// Load previous results

$fp = fopen('testsuite.txt','rt');

if (!$fp)
	$results = array();
else
	{
	$results = unserialize(fgets($fp));
	fclose($fp);
	}

// Test domains

include('whois.main.php');

$whois = new Whois();

set_file_buffer(STDIN, 0);

foreach ($domains as $key => $domain)
	{
	echo "\nTesting $domain ---------------------------------\n";
	$result = $whois->Lookup($domain);
	
	unset($result['rawdata']);
	
	if (!isset($results[$domain]))
		{
		print_r($result);
		$res = get_answer("Add result for $domain");
		
		if ($res)
			{
			// Add as it is
			unset($result['regrinfo']['disclaimer']);
			$results[$domain] = $result;
			save_results();
			}
		
		}
	else
		{
		// Compare with previous result
		unset($result['regrinfo']['disclaimer']);
		unset($results[$domain]['regrinfo']['disclaimer']);
		
		$diff = array_diff_assoc_recursive($result,$results[$domain]);
		
		if (is_array($diff))
			{
			print_r($diff);
			$res = get_answer("Accept differences for $domain");
		
			if ($res)
				{
				// Add as it is
				$results[$domain] = $result;
				save_results();
				}
			}
		else
			echo "Handler for domain $domain gives same results as before ...\n";
		}
	}

save_results();

//--------------------------------------------------------------------------

function save_results()
{
global $results;

$fp = fopen('testsuite.txt','wt');
fputs($fp, serialize($results));
fclose($fp);
}

//--------------------------------------------------------------------------

function get_answer($question)
{
echo "\n------ $question ? (y/n/a/c) ";

while (true)
	{
	$res = trim(fgetc(STDIN));
		
	if ($res=='a') exit();

	if ($res=='c')
		{
		save_results();
		exit();
		}	
	if ($res=='y') return true;
	if ($res=='n') return false;
	}
}

//--------------------------------------------------------------------------

function array_diff_assoc_recursive($array1, $array2)
{
foreach($array1 as $key => $value)
	{
	if (is_array($value))
		{
		if (!is_array($array2[$key]))
			{
			$difference[$key] = array( 'previous' => $array2[$key], 'actual' => $value);
			}
		else 
			{
			$new_diff = array_diff_assoc_recursive($value, $array2[$key]);
			
			if ($new_diff != false)
				{
				$difference[$key] = $new_diff;
				} 
			}
		}
	else
		if (!isset($array2[$key]) || $array2[$key] != $value)
			{
			$difference[$key] = array( 'previous' => $array2[$key], 'actual' => $value);
			}
	}
	
// Search missing items

foreach($array2 as $key => $value)
	{
	if (!isset($array1[$key]))
		$difference[$key] = array( 'previous' => $value, 'actual' => '(missing)');
	}
	
return !isset($difference) ? false : $difference;
}

?>
