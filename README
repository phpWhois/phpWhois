
Whois2.php README v1.0 1999/12/06

This is the long overdue rewrite of whois.php3. You'll have to 
excuse the brevity of this README but I plan to expand on it
later.

Basically, untar the distribution and put the files somewhere
within you php include path. When you want to do a lookup then:

include( "main.whois");
$whois = new Whois("example.com");
$result = $whois->Lookup();

Depending on what kinds of extended handlers are available, $result
can be either a solitary array of $result["rawdata"] or all kinds of
things depending on what methods are available.

There is an extended class called "utils.whois" which contains a
debugging function called showObject(), if you showObject($result)
it will output the total layout of the returned object to the 
web browser.

The latest version of the package and a demo script resides at 
http://www.easydns.com/~markjr/whois2/

By the time this is releases there should also be an article describing
the package on devshed.com at http://www.devshed.com/Server_Side/PHP/whois/

If you're really stuck and can't figure something out, or you want
to contribute an extended class for one of the TLD's, as always, 
email me.

-mark

Mark Jeftovic <markjr@easydns.com>
