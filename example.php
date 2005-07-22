<?php

// $Id$

include('whois.main.php');

$domain = 'example.com';
if(isset($_REQUEST['domain'])) {
	$domain = $_REQUEST['domain'];
}
$whois = new Whois();
$result = $whois->Lookup($domain);

echo "<form method=\"post\" action=\"example.php\">";
echo "<input name=\"domain\" value=\"".$domain."\"/>";
echo "<input type=\"submit\"/>";
echo "</form>";

echo "<pre>";
print_r($result);
echo "</pre>";

?>
