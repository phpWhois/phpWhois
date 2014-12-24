
Whois2 FAQ v1.2 June 06/00 Mark Jeftovic <markjr@easydns.com>

Q's

1.0 How do I tell if a domain is available or not?
1.1 I'm getting "Fatal error:  Call to unsupported or undefined function 
    preg_replace()" why?
1.2 whois2 breaks under PHP4, I get $result["rawdata"] = Array now instead
    of the actual data!

Q & A's

1.0 How do I tell if a domain is available or not?

The big difference between this and version 1 is the absence of the
FOUND flag.

Anyways, for .com/.net/.org you can tell that a domain is available
or not if the regyinfo array is empty.

$whois = new Whois("test.com");
$result = $whois->Lookup();

if(empty($result["regyinfo"])) {
        // available
} else {
        // taken
}

Keep in mind that this isn't 100% surefire. A domain can be dropped from
the registry for nonpayment, and remain in limbo for up to 5 days where
it still cannot be registered. Also, whois servers can lag behind the root
servers by as much as 24 or even 48 hours in extreme cases.

You can also check if there are any nameserver RR's defined (and thus infer 
that the domain is in the root servers) by calling ns_rr_defined($domain)
in the utils extended class.

For other TLD's it's up to you to either grep the results for what you know
to be a "not found" string, or write an extended handler that does a cleaner
job of it, which you can then submit to this project and immortalize yourself :)


1.1 I'm getting "Fatal error:  Call to unsupported or undefined function 
    preg_replace()" why?

All that means is that the version of PHP you're using doesn't have
the perl regular expression module. You can either upgrade or in
the meantime just move netsol.whois out of the php_include_path,
you won't get nicely parsed output for netsol domains but you 
will still at least get the raw output then.

