
$Id$

Introduction
------------

This package contains a Whois (RFC954) library for PHP. It allows
a PHP program to create a Whois object, and obtain the output of
a whois query with the Lookup function.

The response is an array containing, at least, an element 'rawdata', containing the raw output from the whois request.

In addition, if the domain belongs to a registrar for which a special
handler exists, the special handler will parse the output and make
additional elements available in the response. At present, the keys
and values of these additional elements seem to be fairly handler-specific.


Installation
------------

Basically, untar the distribution somewhere and make sure the directory is listed in 'include_path' in your php.ini file.


Example usage
-------------

(see example.php)

include("main.whois");

$whois = new Whois("example.com");
$result = $whois->Lookup();
echo "<pre>";
print_r($result);
echo "</pre>";


Notes
-----

There is an extended class called "utils.whois" which contains a
debugging function called showObject(), if you showObject($result)
it will output the total layout of the returned object to the 
web browser.

The latest version of the package and a demo script resides at 
<http://www.easydns.com/~markjr/whois2/>.

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

Mark Jeftovic <markjr@easydns.org>
David Saez Padros <david@ols.se>
Ross Golder <ross@golder.org>
