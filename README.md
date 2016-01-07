# NEWS

phpWhois v5 is finally out!

Fully rewritten with OOP, supports new domains, fixed IP querying.
Extracting most important information about address:
 - Created, Modified, Expires dates
 - Registrant/Administrative/Technical/Billing Contacts
 - Name servers
 - Status
 - Comments

`whois.iana.org` is queried for unknown addresses first

Whois info is now serializable for easy monitoring for changes

# Introduction

This package contains the Whois (RFC954) library for PHP.
The workflow of this library:
1. Check if handler specified for the given address
2. If handler is not specified, query `whois.iana.org` for the default whois server
3. Query the whois server provided by `whois.iana.org` for the specified address

Code example:
```php
$whois = new \phpWhois\Whois('google.com');
try {
    $response = $whois->lookup();
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage();
}

if (is_null($response)) {
    exit('Can\'t get information about this address');
}

$responseIana = $whois->getResponseIana(); // Response from whois.iana.org
                                           // It provides the whois server for TLD in `refer` and `whois` fields
$responseIanaWhois = $whois->getResponseIanaWhois(); // Response from whois server provided by IANA
$response = $whois->getResponse(); // Response from the whois server specified by library or by user.
                                   // Returned with $whois->lookup()
                                   // If no special whois server was set - this is a copy of `$whois->responseIanaWhois`
```
The client is `\phpWhois\Whois` class. You can query whois servers with `lookup` method. After performing `lookup`

The response is an array containing, at least, an element 'rawdata', containing the raw output from the whois request.

In addition, if the domain belongs to a registrar for which a special handler exists, the special 
handler will parse the output and make additional elements available in the response. The keys of 
these additional elements are described in the file HANDLERS.md.

It fully supports IDNA (internationalized) domains names as defined in RFC3490, RFC3491, RFC3492 and RFC3454.

It also supports ip/AS whois queries which are very useful to trace
SPAM. You just only need to pass the doted quad ip address or the
AS (Autonomus System) handle instead of the domain name. Limited,
non-recursive support for Referral Whois (RFC 1714/2167) is also
provided.

# Useful methods

PhpWhois returns Response object upon querying whois server.
`Response->getQuery()->getAddress()` - returns optimized query (remove www. and few other optimizations)
`Response->getQuery()->getAddressOrig()` - returns unoptimized query
`Response->getParsed()`

# Response structure

Response is based on extracting data rather than parsing all possible data. That means that some fields presented in
raw response may be missing in parsed structure.

Response consists of 10 blocks which were possible to extract from the raw response:
 - Registrant
 - Administrative
 - Technical
 - Billing
 - Registrar
 - Name servers
 - Status
 - Dates
 - Comments
 - KeyValue

# Extending library

phpWhois consists of classes, which can be extended with user-developed implementations
 - `\phpWhois\Provider\ProviderAbstract` - Performs connection to the whois server and fetches results. Currently implemented: WhoisServer and HttpServer
 - `\phpWhois\Handler\HandlerBase` - Handler defines Provider and parsing patterns
 - `\phpWhois\DomainHandlerMap` - set of custom handlers for specific domains
 - `\phpWhois\Query` - contains query for whois server, including optional query parameters
 - `\phpWhois\Response` - contains response from whois server, including raw and parsed data

# Requirements

phpWhois requires PHP 5.5 or later with OpenSSL support to work properly.

Without SSL support you will not be able to query domains which do not have a whois server but that have a https based whois.

# Installation

### Via composer

#### Stable version

`php composer.phar require "phpwhois/phpwhois":"~4.0"`

#### Latest development version

`php composer.phar require "phpwhois/phpwhois":"dev-master"`


# Example usage

(see `example.php`)
```php
// Load composer framework
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require(__DIR__ . '/vendor/autoload.php');
}

use phpWhois\Whois;

$whois = new Whois();
$query = 'example.com';
$result = $whois->lookup($query,false);
echo "<pre>";
print_r($result);
echo "</pre>";
```
If you provide the domain name to query in UTF8, then you
must use:
```php
$result = $whois->lookup($query);
```
If the query string is not in UTF8 then it must be in
ISO-8859-1 or IDNA support will not work.

# What you can query

You can use phpWhois to query domain names, ip addresses and
other information like AS, i.e, both of the following examples
work:
```php
use phpWhois\Whois;
$whois = new Whois();
$result = $whois->lookup('example.com');

$whois = new Whois();
$result = $whois->lookup('62.97.102.115');

$whois = new Whois();
$result = $whois->lookup('AS220');
```
# Using special whois server

Some registrars can give special access to registered whois gateways
in order to have more fine control against abusing the whois services.
The currently known whois services that offer special acccess are:

### ripe

  The new ripe whois server software support some special parameters
  that allow to pass the real client ip address. This feature is only
  available to registered gateways. If you are registered you can use
  this service when querying ripe ip addresses that way:
```php
use phpWhois\Whois;
$whois = new Whois();
$whois->useServer('uk','whois.ripe.net?-V{version},{ip} {query}');
$result = $whois->lookup('62.97.102.115');
```

### whois.isoc.org.il
  This server is also using the new ripe whois server software and
  thus works the same way. If you are registered you can use this service
  when querying `.il` domains that way:

```php
use phpWhois\Whois;
$whois = new Whois();
$whois->useServer('uk','whois.isoc.org.il?-V{version},{ip} {query}');
$result = $whois->lookup('example.co.uk');
```

### whois.nic.uk

  They offer what they call WHOIS2 (see http://www.nominet.org.uk/go/whois2 )
  to registered users (usually Nominet members) with a higher amount of
  permited queries by hour. If you are registered you can use this service
  when querying .uk domains that way:

```php
use phpWhois\Whois;
$whois = new Whois();
$whois->useServer('uk','whois.nic.uk:1043?{hname} {ip} {query}');
$result = $whois->lookup('example.co.uk');
```

This new feature also allows you to use a different whois server than
the preconfigured or discovered one by just calling whois->useServer
and passing the tld and the server and args to use for the named tld.
For example you could use another whois server for `.au` domains that
does not limit the number of requests (but provides no owner 
information) using this:
```php
use phpWhois\Whois;
$whois = new Whois();
$whois->useServer('au','whois-check.ausregistry.net.au');
```
or:
```php
use phpWhois\Whois;
$whois = new Whois();
$whois->useServer('be','whois.tucows.com');
```

to avoid the restrictions imposed by the `.be` whois server

or:

```php
use phpWhois\Whois;
$whois = new Whois();
$whois->useServer('ip','whois.apnic.net');
```

to lookup an ip address at specific whois server (but loosing the
ability to get the results parsed by the appropiate handler)

`useServer` can be called as many times as necessary. Please note that
if there is a handler for that domain it will also be called but
returned data from the whois server may be different than the data
expected by the handler, and thus results could be different.

# Getting results faster

If you just want to know if a domain is registered or not but do not
care about getting the real owner information you can set:

```php
$whois->deepWhois = false;
```

this will tell phpWhois to just query one whois server. For `.com`, `.net` and `.tv` domains and ip addresses this will prevent phpWhois to ask more
than one whois server, you will just know if the domain is registered
or not and which is the registrar but not the owner information.

# UTF-8

PHPWhois will assume that all whois servers return UTF-8 encoded output,
if some whois server does not return UTF-8 data, you can include it in
the `NON_UTF8` array in `whois.servers.php`

# Workflow of getting domain info

1. Call method `phpWhois\Whois::lookup()` with domain name as parameter
2. If second parameter of method is **true** (default), phpWhois will try to convert the domain name to punycode
3. If domain is not listed in predefined handlers (`WHOIS_SPECIAL` at `src/whois.servers.php`), try to query **[tld].whois-servers.net**. If it has ip address, assume that it is valid whois server
4. Try to query found whois server or fill response array with `unknown()` method

# Notes

There is an extended class called `phpWhois\Utils` which contains a
debugging function called `showObject()`, if you `showObject($result)`
it will output the total layout of the returned object to the 
web browser.

The latest version of the package and a demo script resides at 
https://github.com/phpWhois/phpWhois

# Contributing

If you want to add support for new TLD, extend functionality or
correct a bug, feel free to create a new pull request at Github's
repository https://github.com/phpWhois/phpWhois

# Credits

Mark Jeftovic <markjr@easydns.com>

David Saez Padros <david@ols.es>

Ross Golder <ross@golder.org>

Dmitry Lukashin <dmitry@lukashin.ru>
