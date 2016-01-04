<?php

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

$whois = new phpWhois\Whois();

echo "Memory used: ".memory_get_usage()."\n";

try {
    $a = $whois
        ->setAddress('google.am')
        //->lookup('www.HELLO.ru');
        ->lookup();
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage();
}

print_r($a);
//echo $a->getJson();
