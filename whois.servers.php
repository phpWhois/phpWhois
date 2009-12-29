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

$this->DATA_VERSION = '19';

$this->DATA = array(
	'bz'		=> 'gtld',
	'com'		=> 'gtld',
	'jobs'		=> 'gtld',
	'li'		=> 'ch',
	'net'		=> 'gtld',
	'tv'		=> 'gtld',
	'za.org'	=> 'zanet',
	'za.net'	=> 'zanet'
	);

/* Non UTF-8 servers */

$this->NON_UTF8 = array(
	'whois.interdomain.net' => 1
	);

/* If whois Server needs any parameters, enter it here */

$this->WHOIS_PARAM = array(
	'com.whois-servers.net' => 'domain =',
	'net.whois-servers.net' => 'domain =',
	'de.whois-servers.net'	=> '-T dn,ace '
	);

/* TLD's that have special whois servers or that can only be reached via HTTP */

$this->WHOIS_SPECIAL = array(
		'ad'	 => '',
		'ae'	 => 'whois.nic.ae',
		'af'	 => 'whois.nic.af',
		'ai'	 => 'http://whois.offshore.ai/cgi-bin/whois.pl?domain-name={domain}.ai',
		'al'	 => '',
		'az'	 => '',
		'ba'	 => '',
		'bb'	 => 'http://domains.org.bb/regsearch/getdetails.cfm?DND={domain}.bb',
		'bg'	 => 'http://www.register.bg/bg-nic/displaydomain.pl?domain={domain}.bg&search=exist',
		'bi'	 => 'whois.nic.bi',
		'bj'	 => 'whois.nic.bj',
		'by'	 => '',
		'bz'	 => 'whois2.afilias-grs.net',		
		'cy'	 => '',
		'es'	 => '',
		'fm'     => 'http://www.dot.fm/query_whois.cfm?domain={domain}&tld=fm',		
		'jobs'	 => 'jobswhois.verisign-grs.com',				
		'la'	 => 'whois.centralnic.net',		
		'gr'	 => '',
		'gs'     => 'http://www.adamsnames.tc/whois/?domain={domain}.gs',
		'me'	 => 'whois.meregistry.net',
		'mobi'	 => 'whois.dotmobiregistry.net',
		'ms'     => 'http://www.adamsnames.tc/whois/?domain={domain}.ms',
		'mt'	 => 'http://www.um.edu.mt/cgi-bin/nic/whois?domain={domain}.mt',				
		'pe'	 => 'http://nic.pe/detpublic.php?decid=B&ndom={domain}.pe',
		'pr'	 => 'whois.uprr.pr',
		'pro'	 => 'whois.registry.pro',		
		'sc'     => 'whois2.afilias-grs.net',		
		'tc'     => 'http://www.adamsnames.tc/whois/?domain={domain}.tc',
		'tf'     => 'http://www.adamsnames.tc/whois/?domain={domain}.tf',
		've'	 => 'whois.nic.ve',
		'vg'     => 'http://www.adamsnames.tc/whois/?domain={domain}.vg',
		// Second level
		'net.au' => 'whois.aunic.net',		
		'ae.com' => 'whois.centralnic.net',
		'br.com' => 'whois.centralnic.net',
		'cn.com' => 'whois.centralnic.net',
		'de.com' => 'whois.centralnic.net',
		'eu.com' => 'whois.centralnic.net',
		'hu.com' => 'whois.centralnic.net',		
		'jpn.com'=> 'whois.centralnic.net',
		'kr.com' => 'whois.centralnic.net',
		'gb.com' => 'whois.centralnic.net',
		'no.com' => 'whois.centralnic.net',
		'qc.com' => 'whois.centralnic.net',
		'ru.com' => 'whois.centralnic.net',
		'sa.com' => 'whois.centralnic.net',
		'se.com' => 'whois.centralnic.net',		
		'za.com' => 'whois.centralnic.net',
		'uk.com' => 'whois.centralnic.net',		
		'us.com' => 'whois.centralnic.net',
		'uy.com' => 'whois.centralnic.net',		
		'gb.net' => 'whois.centralnic.net',
		'se.net' => 'whois.centralnic.net',
		'uk.net' => 'whois.centralnic.net',		
		'za.net' => 'whois.za.net',
		'za.org' => 'whois.za.net',
		'co.za'  => 'http://co.za/cgi-bin/whois.sh?Domain={domain}.co.za',
		'org.za' => 'http://www.org.za/cgi-bin/rwhois?domain={domain}.org.za&format=full'
		);
		
/* handled gTLD whois servers */

$this->WHOIS_GTLD_HANDLER = array(
		'whois.bulkregister.com'			=> 'enom',
		'whois.dotregistrar.com'			=> 'dotster',
		'whois.namesdirect.com'				=> 'dotster',
		'whois.psi-usa.info'				=> 'psiusa',
		'whois.www.tv'						=> 'tvcorp',
		'whois.tucows.com'					=> 'opensrs',
		'whois.35.com'						=> 'onlinenic'
		);
		
/* Non ICANN TLD's */

$this->WHOIS_NON_ICANN = array (
		'agent'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'agente'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'america'	=> 'http://www.adns.net/whois.php?txtDOMAIN={domain}.{tld}',
		'amor'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'amore'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'amour'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'arte'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'artes'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'arts'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'asta'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'auction'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'auktion'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'boutique'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'chat'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'chiesa'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'church'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'cia'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'ciao'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'cie'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'club'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'clube'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'com2'		=> 'http://www.adns.net/whois.php?txtDOMAIN={domain}.{tld}',
		'deporte'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'ditta'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'earth'		=> 'http://www.adns.net/whois.php?txtDOMAIN={domain}.{tld}',
		'eglise'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'enchere'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'escola'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'escuela'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'esporte'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'etc'		=> 'http://www.adns.net/whois.php?txtDOMAIN={domain}.{tld}',
		'famiglia'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'familia'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'familie'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'family'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'free'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'hola'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'game'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'ges'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'gmbh'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'golf'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'gratis'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'gratuit'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'iglesia'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'igreja'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'inc'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'jeu'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'jogo'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'juego'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'kids'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'kirche'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'krunst'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'law'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'legge'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'lei'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'leilao'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'ley'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'liebe'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'lion'		=> 'http://www.adns.net/whois.php?txtDOMAIN={domain}.{tld}',
		'llc'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'llp'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'loi'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'loja'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'love'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'ltd'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'makler'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'med'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'mp3'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'not'		=> 'http://www.adns.net/whois.php?txtDOMAIN={domain}.{tld}',
		'online'	=> 'http://www.adns.net/whois.php?txtDOMAIN={domain}.{tld}',
		'recht'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'reise'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'resto'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'school'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'schule'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'scifi'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'scuola'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'shop'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'soc'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'spiel'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'sport'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'subasta'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'tec'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'tech'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'tienda'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'travel'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'turismo'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'usa' 		=> 'http://www.adns.net/whois.php?txtDOMAIN={domain}.{tld}',		
		'verein'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'viaje'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'viagem'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'video'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'voyage'	=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'xxx'		=> 'http://www.new.net/search_whois.tp?domain={domain}&tld={tld}',
		'z'			=> 'http://www.adns.net/whois.php?txtDOMAIN={domain}.{tld}'
		);
?>
