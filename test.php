<?php

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/*$whois = new phpWhois\Whois();

echo "Memory used: ".memory_get_usage()."\n";

try {
    $a = $whois
        ->setAddress('google.am')
        //->lookup('www.HELLO.ru');
        ->lookup();
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage();
}

print_r($a);*/

$idna = new TrueBV\Punycode();

$domain = 'www.президент.рф';

$encoded = $idna->encode($domain);

echo $encoded."\n";

/*$domain = 'hello.xn--mgberp4a5d4ar';
$decoded = $idna->decode($domain);

echo $decoded."\n";*/