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
information. It has four subkeys: 'servers' which is an array with
one entry for each whois server who returned the data, 'referrer' the
web address of the registry, 'registar' the company name of the registry
and 'type' which can be 'domain', 'ip' or 'AS'. The 'servers' array
has subkeys 'server' the whois server, 'port' the whois server port and
'args' the query sent to the server.

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
		nserver	-> array where the key is the canonical name
			   of each nameserver and the value is the
			   ip adresss (if none) of the server.
		status  -> status of the domain (registry dependant)
		changed -> date of last change
		created	-> creation date
		expires -> expire date
		sponsor	-> registry partner where the domain was
			   registered
		referer -> sponsor's URL
		handle  -> domain handle
		source  -> who gives this information
		dnssec  -> domains has dnssec (boolean)

	network
	-------
	Only when dealing with ip addresses. Could contain the
	following subkeys:

		name	-> network/AS name		
		inetnum	-> network ip address range
		desc	-> network description
		mnt-by	-> who provided that network
		mnt-lower> who provided that network 
		nserver -> name servers in listed order that
			   provide inverse resolution for that net
		status	-> status of the network (registry dependant)
		remarks -> remarks provided by the registry
                changed -> date of last change
                created -> creation date
                handle  -> network/AS handle
                source  -> who gives this information

	AS
	--

	Only when dealing with Autonomus systems, could contain
	the same subkeys as network.

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
				   subkeys are street, city,
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

    When information is requested on ip addresses any of those
	keys could be an array which will contain all data found on
	different whois or rwhois servers (each owner, admin, tech,
	etc ... found in each query).

Not all handlers fill values in each of the keys defined by the
Common Object Model as not all registries return the same amount
of data about a domain or ip address. Also there are some differences
on the format returned for some keys.

Dates (created/changed/expires) are always returned in the format
yyyy-mm-dd when a handler is defined for the returned data.


Writing handlers
----------------

Writing handlers is easy, just look at how some of them are coded.
If you write a new handler, please try to map as many as possible
returned data to keys defined by the 'Common Object Model'. You can
create new keys if need, but please do not do create new keys where
existing predefined keys exists. Nevertheless all handlers submited
will be checked before they are added to phpWhois distribution. 

If some tld needs special parameters or can be queried in
another whois servers or web base whois servers you can setup
rules in whois.servers.php so phpWhois can do the right thing.

There is also a naming schema that must be followed, country
handlers are named whois.XX.php, where XX is the iso country
code. The handler must also define __XX_HANDLER__ and implement
a class named xx_handler with a function named parse that takes
two arguments: $data_str and $query. $data_str['rawdata']
contains the raw output of the query and is what need to be parsed
in order to generate the Common Object Model. $query contains
the domain, ip adrress or AS that it's being queried. That function
must return an array with any available result in the format defined
by this document. Country handlers need not to be defined in
the file whois.servers.php, only when you want to use some handler
for several different country domains you need to add it to the array
DATA where the key is the iso country code and the value the handler
name (xx).

Handlers for .com/.net/.tv domains are handled by whois.gtld.php
and named whois.gtld.xxx.php where xxx is the 'midname' (for example,
for whois.srsplus.com, the midname is srsplus) of the whois server who
provides information for that domains. If you want to reuse another
handler of your handler 'midname' conflicts with any existing gtld
handler you could define the handler name in the array WHOIS_GTLD_HANDLER
in the file whois.servers.php. It must be implemented the same way as
country handlers.

Some useful utility functions have been written to aid in developing
handlers. They are contained in whois.parser.php. Almost all handlers
use functions provided by that file. You can see how they work by
looking into the code. You also have a handler.template.php file
with the squeleton of a handler.

Please try to mimic the coding style of the other handlers, as this
will make it easier for other people to understand and maintain.

Some support functions have been developed to help you write new
handlers, those functions are stored on the following files:

- generic_parser_a:

  contains code to parse whois outputs in RPSL format, like this one.
  You could take a look at whois.at.php to see how you could use it:

  domain:         nic.at
  registrant:     NAIV1117337-NICAT
  admin-c:        NAR567002-NICAT
  tech-c:         GW502425-NICAT
  zone-c:         GW502425-NICAT
  nserver:        ns3ext.univie.ac.at
  nserver:        ns4ext.univie.ac.at
  nserver:        ns5.univie.ac.at
  nserver:        ns9.univie.ac.at
  changed:        20030616 12:54:18
  source:         AT-DOM
 
  personname:     
  organization:   NIC.AT Internet Verwaltungs- und Betriebsges.m.b.H.
  street address: Jakob-Haringerstrasse 8
  postal code:    A-5020
  city:           Salzburg
  country:        Austria
  phone:          +43662466920
  fax-no:         +43662466929
  e-mail:         office@nic.at
  nic-hdl:        NAIV1117337-NICAT
  changed:        20020614 17:29:04
  source:         AT-DOM
 
  personname:     NIC.AT Role
  organization:   
  street address: NIC.AT Internet Verwaltungs- und Betriebsgesellschaft m.b.H.
  street address: Jakob-Haringerstrasse 8
  street address: A-5020 Salzburg
  street address: Austria
  postal code:    
  city:           
  country:        
  phone:          +43 662 4669 0
  fax-no:         +43 662 4669 19
  e-mail:         nic-at@nic.at
  nic-hdl:        NAR567002-NICAT
  changed:        20010223 12:52:13
  source:         AT-DOM
 
  personname:     Gerhard Winkler
  organization:   
  street address: Vienna University
  street address: Computer Center - ACOnet
  street address: Universitaetsstrasse 7
  street address: A-1010 Vienna
  street address: Austria
  postal code:    
  city:           
  country:        
  phone:          +43 1 4277 140 35
  fax-no:         +43 1 4277 9140
  e-mail:         gerhard.winkler@univie.ac.at
  nic-hdl:        GW502425-NICAT
  changed:        20001205 14:06:15
  source:         AT-DOM

- generic_parser_b:

  contains code to parse whois outputs like this one, you could
  take a look at whois.neulevel.php to see how you could use it:

  Domain Name:                                 NIC.BIZ
  Domain ID:                                   D714-BIZ
  Sponsoring Registrar:                        REGISTRY REGISTRAR
  Domain Status:                               clientDeleteProhibited
  Domain Status:                               clientTransferProhibited
  Domain Status:                               clientUpdateProhibited
  Domain Status:                               serverDeleteProhibited
  Domain Status:                               serverTransferProhibited
  Domain Status:                               serverUpdateProhibited
  Registrant ID:                               NEULEVEL1
  Registrant Name:                             Customer Support
  Registrant Address1:                         Loudoun Tech Center
  Registrant Address2:                         45980 Center Oak Plaza
  Registrant City:                             Sterling
  Registrant State/Province:                   Virginia
  Registrant Postal Code:                      20166
  Registrant Country:                          United States
  Registrant Country Code:                     US
  Registrant Phone Number:                     +1.5714345757
  Registrant Facsimile Number:                 +1.5714345758
  Registrant Email:                            support@neulevel.biz
  Administrative Contact ID:                   NEULEVEL1
  Administrative Contact Name:                 Customer Support
  Administrative Contact Address1:             Loudoun Tech Center
  Administrative Contact Address2:             45980 Center Oak Plaza
  Administrative Contact City:                 Sterling
  Administrative Contact State/Province:       Virginia
  Administrative Contact Postal Code:          20166
  Administrative Contact Country:              United States
  Administrative Contact Country Code:         US
  Administrative Contact Phone Number:         +1.5714345757
  Administrative Contact Facsimile Number:     +1.5714345758
  Administrative Contact Email:                support@neulevel.biz
  Billing Contact ID:                          NEULEVEL1
  Billing Contact Name:                        Customer Support
  Billing Contact Address1:                    Loudoun Tech Center
  Billing Contact Address2:                    45980 Center Oak Plaza
  Billing Contact City:                        Sterling
  Billing Contact State/Province:              Virginia
  Billing Contact Postal Code:                 20166
  Billing Contact Country:                     United States
  Billing Contact Country Code:                US
  Billing Contact Phone Number:                +1.5714345757
  Billing Contact Facsimile Number:            +1.5714345758
  Billing Contact Email:                       support@neulevel.biz
  Technical Contact ID:                        NEULEVEL1
  Technical Contact Name:                      Customer Support
  Technical Contact Address1:                  Loudoun Tech Center
  Technical Contact Address2:                  45980 Center Oak Plaza
  Technical Contact City:                      Sterling
  Technical Contact State/Province:            Virginia
  Technical Contact Postal Code:               20166
  Technical Contact Country:                   United States
  Technical Contact Country Code:              US
  Technical Contact Phone Number:              +1.5714345757
  Technical Contact Facsimile Number:          +1.5714345758
  Technical Contact Email:                     support@neulevel.biz
  Name Server:                                 NS1.NEULEVEL.BIZ
  Name Server:                                 NS2.NEULEVEL.BIZ
  Name Server:                                 NS4.NEULEVEL.BIZ
  Name Server:                                 NS3.NEULEVEL.BIZ
  Created by Registrar:                        REGISTRY REGISTRAR
  Last Updated by Registrar:                   NEULEVELCSR
  Domain Registration Date:                    Wed Nov 07 00:01:00 GMT 2001
  Domain Expiration Date:                      Sat Nov 06 23:59:00 GMT 2004
  Domain Last Updated Date:                    Fri Nov 07 18:59:11 GMT 2003 

- get_blocks/get_contacts:

  contains code to parse whois outputs like this one, you could
  take a look at whois.ch.php to see how you could use it:

  Domain name:
  nic.ch

  Holder of domain name:
  SWITCH Internet Domains
  Dana Djurdjevic
  Neumühlequai 6
  CH-8001 Zürich
  Switzerland
  hostmaster@switch.ch
  Contractual Language: English

  Technical contact:
  SWITCH Geschäftsstelle
  Andrea Tognola
  Network
  Limmatquai 138
  CH-8001 Zürich
  Switzerland
  hostmaster@switch.ch

  Name servers:
  merapi.switch.ch	[130.59.211.10]
  scsnms.switch.ch	[130.59.1.30]
  scsnms.switch.ch	[130.59.10.30]

  Date of last registration:
  31.12.1995

  Date of last modification:
  22.12.2003

Credits
-------

Mark Jeftovic <markjr@easydns.com>
David Saez Padros <david@ols.es>
Ross Golder <ross@golder.org>
