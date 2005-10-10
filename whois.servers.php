<?php
/*
Whois.php        PHP classes to conduct whois queries

Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic

Maintained by David Saez (david@ols.es)

For the most recent version of this package visit:

http://www.phpwhois.org

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

/* servers.whois	v18   Markus Welters	2004/06/25 */
/* servers.whois	v17	ross golder	2003/02/09 */
/* servers.whois	v16	mark jeftovic	2001/02/28 */

$this->DATA_VERSION = '18';

$this->DATA = array(
	'aero'		=> 'aero',
	'ag'		=> 'ag',
	'at'		=> 'at',
	'au'		=> 'au',
	'biz'		=> 'biz',
	'be'		=> 'be',
	'br'		=> 'br',
	'ca'		=> 'ca',
	'ch'		=> 'ch',
	'cn'		=> 'cn',
	'com'		=> 'gtld',
	'coop'		=> 'coop',
	'de'		=> 'de',
	'es'		=> 'es',
	'fm'		=> 'fm',
	'hu'		=> 'hu',
	'info'		=> 'info',
	'is'		=> 'is',
	'li'		=> 'ch',
	'lu'		=> 'lu',
	'lt'		=> 'lt',
	'museum'	=> 'museum',
	"mx"		=> "mx",
	"net"		=> "gtld",
	"nl"		=> "nl",
	"nu"		=> "nu",
	"org"		=> "org",
	"se"		=> "se",
	"tv"		=> "gtld",
	"uk"		=> "uk",
	"us"		=> "us",
	"ws"		=> "ws",
	'za.org'	=> 'za',
	'za.net'	=> 'za'
	);

/* If whois Server needs any parameters, enter it here */

$this->WHOIS_PARAM = array(
	'com.whois-servers.net' => '=',
	'net.whois-servers.net' => '=',
	'de.whois-servers.net'	=> '-T dn,ace '
	);

/* TLD's that have special whois servers or that can only be reached via HTTP */

$this->WHOIS_SPECIAL = array(
		'ad'	 => '',
		'ai'	 => 'http://whois.offshore.ai/cgi-bin/whois.pl?domain-name={domain}.{tld}',
		'al'	 => '',
		'az'	 => '',
		'ba'	 => '',
		'bb'	 => 'http://domains.org.bb/regsearch/getdetails.cfm?DND={domain}.{tld}',
		'bg'	 => 'http://www.register.bg/bg-nic/displaydomain.pl?domain={domain}.{tld}&search=exist',
		'bi'	 => 'whois.nic.bi',
		'bj'	 => 'whois.nic.bj',
		'by'	 => '',
		'es'     => 'https://www.nic.es/esnic/servlet/WhoisControllerHTML?dominio={domain}.{tld}&tipo=dominio',
		'co.za'  => 'http://co.za/cgi-bin/whois.sh?Domain={domain}.{tld}',
		'fm'     => 'http://www.dot.fm/query_whois.cfm?domain={domain}&tld=fm',
		'gs'     => 'http://www.adamsnames.tc/whois/?domain={domain}.{tld}',
		'in'     => 'whois.ncst.ernet.in',
		'ms'     => 'http://www.adamsnames.tc/whois/?domain={domain}.{tld}',
		'net.au' => 'whois.aunic.net',
		'pe'	 => 'http://nic.pe/detpublic.php?decid=B&ndom={domain}.{tld}',
		'tc'     => 'http://www.adamsnames.tc/whois/?domain={domain}.{tld}',
		'tf'     => 'http://www.adamsnames.tc/whois/?domain={domain}.{tld}',
		'vg'     => 'http://www.adamsnames.tc/whois/?domain={domain}.{tld}',
		'za.net' => 'whois.za.net',
		'za.org' => 'whois.za.net'
		);
?>
