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
echo $a->getJson();*/

$raw =
"% IANA WHOIS server
% for more information on IANA, visit http://www.iana.org
% This query returned 1 object

refer:        whois.nic.space

domain:       SPACE

organisation: DotSpace Inc.
address:      Directiplex
address:      Next to Andheri Subway
address:      Old Nagardas Road, Andheri (East)
address:      Mumbai
address:      Maharashtra
address:      400069
address:      India

contact:      administrative
name:         Manager
organisation: DotSpace Inc.
address:      Directiplex
address:      Next to Andheri Subway
address:      Old Nagardas Road, Andheri (East)
address:      Mumbai
address:      Maharashtra
address:      400069
address:      India
phone:        +1.4154494774x8522
fax-no:       +91.2230797508
e-mail:       admin@radixregistry.com

contact:      technical
name:         CTO
organisation: CentralNic
address:      35-39 Moorgate
address:      London EC2R 6AR
address:      United Kingdom
phone:        +44.2033880600
fax-no:       +44.2033880601
e-mail:       tld.ops@centralnic.com

nserver:      A.NIC.SPACE 194.169.218.51 2001:67c:13cc:0:0:0:1:51
nserver:      B.NIC.SPACE 185.24.64.51 2a04:2b00:13cc:0:0:0:1:51
nserver:      C.NIC.SPACE 185.38.99.4 2a02:e180:3:0:0:0:0:4
nserver:      D.NIC.SPACE 108.59.161.4 2a02:e180:4:0:0:0:0:4
ds-rdata:     44251 8 1 36ACB68B734DFE465CC1112F9DAC08B8B66627CC
ds-rdata:     44251 8 2 A82D8ED2B07D66D6E7AF375E0E44B22A82F4479AD45F5D8E1859DF6FC170E67C

whois:        whois.nic.space

status:       ACTIVE
remarks:      Registration information: http://radixregistry.com/

created:      2014-05-22
changed:      2015-07-24
source:       IANA";

$parser = new phpWhois\Parser\ParserA($raw);

$parsed = $parser->parse();

$response = new \phpWhois\Response();
$response->setParsed($parsed);

echo 'refer: '.$response->getByKey('created');

echo "\nMemory used: ".memory_get_usage()."\n";

echo "\n";
