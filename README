
$Id$

Introduction
------------

This package contains a Whois (RFC954) library for PHP. It allows
a PHP program to create a Whois object, and obtain the output of
a whois query with the Lookup function.

The response is an array containing, at least, an element 'rawdata',
containing the raw output from the whois request.

In addition, if the domain belongs to a registrar for which a special
handler exists, the special handler will parse the output and make
additional elements available in the response. The keys of these
additional elements are described in the file HANDLERS.

It also supports ip/AS whois queries which are very useful to trace
SPAM. You just only need to pass the doted quad ip address or the
AS (Autonomus System) handle instead of the domain name. Limited,
non-recursive support for Referral Whois (RFC 1714/2167) is also
provided.

Requirements
------------

phpWhois requires PHP 4.3.0 or better with OpenSSL support to
work properly. Without SSL support you will not be able to
query domains which do not have a whois server but that have
a https based whois. Also, you can run it in lower PHP versions
but without timeout control. phpWhois will not work with PHP
versions below 4.1.0

Installation
------------

Basically, untar the distribution somewhere and make sure the directory
is listed in 'include_path' in your php.ini file.


Example usage
-------------

(see example.php)

include('whois.main.php');

$whois = new Whois();
$result = $whois->Lookup('example.com');
echo "<pre>";
print_r($result);
echo "</pre>";

What you can query
------------------

You can use phpWhois to query domain names, ip addresses and
other information like AS, i.e, both of the following examples
work:

$whois = new Whois();
$result = $whois->Lookup('example.com');

$whois = new Whois();
$result = $whois->Lookup('62.97.102.115');

$whois = new Whois();
$result = $whois->Lookup('AS220');

Notes
-----

There is an extended class called "whois.utils.php" which contains a
debugging function called showObject(), if you showObject($result)
it will output the total layout of the returned object to the 
web browser.

The latest version of the package and a demo script resides at 
<http://phpwhois.sourceforge.net/>

There is also be an article describing the package on devshed.com
at <http://www.devshed.com/Server_Side/PHP/whois/>


Support/Patches
---------------

If you're really stuck and can't figure something out, or you want
to contribute an extended class for one of the TLD's, file a patch
or support request in the SourceForge tracker. One of the developers
will get around to applying or responding.
<http://sourceforge.net/projects/phpwhois>


Credits
-------

Mark Jeftovic <markjr@easydns.com>
David Saez Padros <david@ols.es>
Ross Golder <ross@golder.org>
