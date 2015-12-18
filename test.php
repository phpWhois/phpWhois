<?php

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

$whois = new phpWhois\Whois();

echo "Memory used: ".memory_get_usage()."\n";

try {
    $a = $whois
        //->setAddress(true)
        ->lookup('ru');
    echo $a;
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage();
}

echo "Memory used: ".memory_get_usage()."\n";

echo "\n";
