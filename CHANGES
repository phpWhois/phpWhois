2001/02/28
		-batch add of many updates
		-uknic.whois updated by David Saez Padros 
		-added dotster and chnic for .ch and .li, also by David Saez Padros
		-.at whois server now whois.nic.at
		
2000/12/12
v2.3	-PHP4 BUG IS FIXED!!!! Very special thanks to all who submitted
		fixes, used one provided by Stephen Leavitt 
		<stephen_j_leavitt@hotmail.com> as it was the easiest and backwards
		compatible to PHP3
		-added Enom handler, also by Stephen Leavitt 
		<stephen_j_leavitt@hotmail.com>
		-changed .ca whois to whois.cira.ca, using same handler for now.
2000/08/14
		-added brnic.whois country handler
		-major revision of servers.whois, many adds (crossreferenced
		against geektools list at 
		http://www.geektools.com/dist/whoislist.gz)
		-dropped all ORSC TLD's, either their whois servers weren't
		working or it was clear there was no functioning registry
		if they were. (From here on in we stick to the IANA
		legacy root TLD's) 
2000/08/07
v2.2-3		-gtld.whois, "TUCOWS.COM INC." now, "TUCOWS.COM, INC."
		an unannounced change by the NSI registry once again
		breaks scripts all over the world...(thanks to 
		Fred Andrews <fandrews@Technologist.com> for the report
		and fix on this)
		-servers.whois, added .ke Kenyan whois server, thanks
		to "Peter Anampiu" <Anampiu@4bil.net> for digging that up.:x

2000/05/27
v2.2-2		-ouch! params in implode() are backwards 

2000/04/06
v2.2-1		-new classes for bulkregister, openSRS and melbourneIT
		by Jeremiah Bellomy <jeremiah@emphasys.net>

2000/03/16
v2.1-4		-servers.whois, added .st

2000/03/08
v2.1-4		-servers.whois, fixed .no address, added .as

2000/01/26
v2.1-4
main.whois	-fixed Connect() so it wouldn't attempt to connect
		to an unset server (Query["server"] is null on
		pass 2 if domain isn't in the registry whois)
		-added rudimentry "not found" code to sample script

2000/01/02
v2.1-3		
main.whois	-fixed GetTld() so "churchuk.com" 's tld wouldn't
		mistakenly be set to nominet's "uk.com" tld.
		-fixed Lookup() was always using char by char reads
		regardless of $this->BUFFER value
servers.whois 	-added nominet's se.com, se.net and no.com

2000/01/01
2.1-2
main.whois	-fixed Process() so it would only include the handler if
		it wasn't already defined. Trying queries inside a loop
		would fail on the second iteration.

