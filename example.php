<?php

// $Id$

include("main.whois");

$whois = new Whois("example.com");
$result = $whois->Lookup();
echo "<pre>";
print_r($result);
echo "</pre>";

?>
