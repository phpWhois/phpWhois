Introduction
------------

Handlers are pieces of code that parse the raw whois output and try
to add some keys to the result returned by phpWhois. In previous
versions there was no standard about how these keys should be named
and organized, which make them registry specific. Now all handlers
have been standarized so they return a known set of keys. This is what
we call 'Common Object Model'.


Common Object Model
-------------------

The keys that you could find in the result array returned by phpWhois
are 'rawdata' , 'regyinfo' and 'regrinfo'. 

rawdata is always returned as it's filled by phpWhois itself. It
contains the raw text output returned by the whois server.

regyinfo contains information about the registry who returned that
information. It has three subkeys: 'whois' (the whois server who
returned the data), 'referrer' (the web address of the registry)
and 'registar' (the company name of the registry).

regrinfo holds the information about the domain itself. It could have
the following subkeys:

	disclaimer
	----------
	Contains the disclaimer returned by the registry.

	registered
	----------
	Contains Yes or No and indicates if the domain or ip
	address has been found (it exists).

	domain
	------
	Only when dealing with domains. Could contain the
	following subkeys:

		name	-> domain name
		desc	-> description of the domain
		nserver	-> array containing canonical names
			   of all nameservers for that domain
			   listed i n order. Optionally it can
			   also include the name server ip address
			   following the canonical name.
		status  -> status of the domain (registry dependant)
		changed -> date of last change
		created	-> creation date
		expires -> expire date
		sponsor	-> registry partner where the domain was
			   registered
		handle  -> domain handle
		source  -> who gives this information

	network
	-------
	Only when dealing with ip addresses. Could contain the
	following subkeys:

		name	-> network name
		inetnum	-> network ip address range
		desc	-> network description
		host_ip	-> ip address that was tested
		host_name> host name obtained doing reverse dns
			   lookup on host_ip
		mnt-by	-> who provided that network
		mnt-lower> who provided that network 
		nserver -> name servers in listed order that
			   provide inverse resolution for that net
		status	-> status of the network (registry dependant)
		remarks -> remarks provided by the registry
                changed -> date of last change
                created -> creation date
                handle  -> domain handle
                source  -> who gives this information

	owner,admin,tech,zone,billing,abuse
	-----------------------------------

	All of these possible keys hold information about the different
	contacts of the domain or ip address. They all could have the
	same subkeys, that are:

		organization	-> organization name
		name		-> organization responsible
		type		-> type of contact
		address		-> array containing the address, the
				   keys of that array could be just
				   numbers, could have predefined
				   subkeys or could be amix of numbers
				   and predefined subkeys. Predefined
				   subkeys are street, street2, city,
				   state, pcode and country
		phone		-> phone, could also be an array of
				   phone numers
		fax		-> fax, same behaviour as phone
		email		-> email, same behaviour as phone
		handle		-> contact handle
		mnt-by		-> who provided that contact
		created		-> creation date
		changed		-> last change date
		source		-> who provided that information
		remarks		-> remarks

Not all handlers fill values in each of the keys defined by the
Common Object Model as not all registries return the same amount
of data about a domain or ip address. Also there are some differences
on the format returned for some keys (mainly the keys that reflect
dates). 


Writing handlers
----------------

Writing handlers is easy, just look at how some of them are coded.
If you write a new handler, please try to map as many as possible
returned data to keys defined by the 'Common Object Model'. You can
create new keys if need, but please do not do create new keys where
existing predefined keys exists. Nevertheless all handlers submited
will be checked before they are added to phpWhois distribution. 

Some useful utility functions have been written to aid in developing
handlers. They are contained in generic.whois, generic2.whois and
generic3.whois. Almost all handlers use functions provided by those
files. You can see how they work by looking into the code.

Please try to mimic the coding style of the other handlers, as this
will make it easier for other people to understand and maintain.


Credits
-------

Mark Jeftovic <markjr@easydns.com>
David Saez Padros <david@ols.es>
Ross Golder <ross@golder.org>
