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

/* lunic.whois  2.0	David Saez <david@ols.es> 2003/09/08 */
/* cnnic.whois	1.0	Chewy - 2003/Sep/03 */

if (!defined('__CN_HANDLER__'))
	define('__CN_HANDLER__', 1);

require_once('whois.parser.php');

class cn_handler
	{

	function parse($data_str, $query)
		{
		$items = array(
                'Domain Name:' => 'domain.name',
                'Domain Status:' => 'domain.status',
                'Name Server:' => 'domain.nserver.',
                'Registration Date:' => 'domain.created',
                'Expiration Date:' => 'domain.expires',
                'Sponsoring Registrar:' => 'domain.sponsor',
                'Registrant Name:' => 'owner.name',
                'Registrant Organization:' => 'owner.organization',
                'Registrant Address:' => 'owner.address.address',
                'Registrant Postal Code:' => 'owner.address.pcode',
                'Registrant City:' => 'owner.address.city',
                'Registrant Country Code:' => 'owner.address.country',
                'Registrant Email:' => 'owner.email',
                'Registrant Phone Number:' => 'owner.phone',
                'Registrant Fax:' => 'owner.fax',
                'Administrative Name:' => 'admin.name',
                'Administrative Organization:' => 'admin.organization',
                'Administrative Address:' => 'admin.address.address',
                'Administrative Postal Code:' => 'admin.address.pcode',
                'Administrative City:' => 'admin.address.city',
                'Administrative Country Code:' => 'admin.address.country',
                'Administrative Email:' => 'admin.email',
                'Administrative Phone Number:' => 'admin.phone',
                'Administrative Fax:' => 'admin.fax',
                'Technical Name:' => 'tech.name',
                'Technical Organization:' => 'tech.organization',
                'Technical Address:' => 'tech.address.address',
                'Technical Postal Code:' => 'tech.address.pcode',
                'Technical City:' => 'tech.address.city',
                'tec-country:' => 'tech.address.country',
                'Technical Email:' => 'tech.email',
                'Technical Phone Number:' => 'tech.phone',
                'Technical Fax:' => 'tech.fax',
                'Billing Name:' => 'billing.name',
                'Billing Organization:' => 'billing.organization',
                'Billing Address:' => 'billing.address.address',
                'Billing Postal Code:' => 'billing.address.pcode',
                'Billing City:' => 'billing.address.city',
                'Billing Country Code:' => 'billing.address.country',
                'Billing Email:' => 'billing.email',
                'Billing Phone Number:' => 'billing.phone',
                'Billing Fax:' => 'billing.fax'
		            );

		$r['regyinfo'] = array(
                'referrer' => 'http://www.cnnic.net.cn',
                'registrar' => 'China NIC'
                );
		$r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items, 'ymd');
		return ($r);
		}
	}

?>
