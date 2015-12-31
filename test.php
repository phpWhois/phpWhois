<?php

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

$whois = new phpWhois\Whois();

echo "Memory used: ".memory_get_usage()."\n";

/*try {
    $a = $whois
        ->setAddress('Www.GOOgle.ru')
        //->lookup('www.HELLO.ru');
        ->lookup();
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage();
}
print_r($a->getData());
echo $a->getJson();

echo "\nMemory used: ".memory_get_usage()."\n";

echo "\n";*/

$whois->setHandler();