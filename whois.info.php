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

/* info.whois   2.0  David Seaz - updated to common object model */
/* info.whois	1.0  David Saez Padros <david@ols.es> */

if (!defined('__INFO_HANDLER__'))
	define('__INFO_HANDLER__', 1);

require_once('whois.parser.php');

class info_handler
	{

	function parse($data_str, $query)
		{

		$items = array(
                  'Domain ID:' => 'domain.handle',
                  'Domain Name:' => 'domain.name',
                  'Created On:' => 'domain.created',
                  'Last Updated On:' => 'domain.changed',
                  'Expiration Date:' => 'domain.expires',
                  'Sponsoring Registrar:' => 'domain.sponsor',
                  'Status:' => 'domain.status',
                  'Name Server:' => 'domain.nserver.',
                  'Registrant ID:' => 'owner.handle',
                  'Registrant Name:' => 'owner.name',
                  'Registrant Organization:' => 'owner.organization',
                  'Registrant Street1:' => 'owner.address.street.0',
                  'Registrant Street2:' => 'owner.address.street.1',
                  'Registrant City:' => 'owner.address.city',
                  'Registrant State/Province:' => 'owner.address.state',
                  'Registrant Postal Code:' => 'owner.address.pcode',
                  'Registrant Country:' => 'owner.address.country',
                  'Registrant Phone:' => 'owner.phone',
                  'Registrant FAX:' => 'owner.fax',
                  'Registrant Email:' => 'owner.email',
                  'Admin ID:' => 'admin.handle',
                  'Admin Name:' => 'admin.name',
                  'Admin Organization:' => 'admin.organization',
                  'Admin Street1:' => 'admin.address.street.0',
                  'Admin Street2:' => 'admin.address.street.1',
                  'Admin City:' => 'admin.address.city',
                  'Admin State/Province:' => 'admin.address.state',
                  'Admin Postal Code:' => 'admin.address.pcode',
                  'Admin Country:' => 'admin.address.country',
                  'Admin Phone:' => 'admin.phone',
                  'Admin FAX:' => 'admin.fax',
                  'Admin Email:' => 'admin.email',
                  'Tech ID:' => 'tech.handle',
                  'Tech Name:' => 'tech.name',
                  'Tech Organization:' => 'tech.organization',
                  'Tech Street1:' => 'tech.address.street.0',
                  'Tech Street2:' => 'tech.address.street.1',
                  'Tech City:' => 'tech.address.city',
                  'Tech State/Province:' => 'tech.address.state',
                  'Tech Postal Code:' => 'tech.address.pcode',
                  'Tech Country:' => 'tech.address.country',
                  'Tech Phone:' => 'tech.phone',
                  'Tech FAX:' => 'tech.fax',
                  'Tech Email:' => 'tech.email',
                  'Billing ID:' => 'billing.handle',
                  'Billing Name:' => 'billing.name',
                  'Billing Organization:' => 'billing.organization',
                  'Billing Street1:' => 'billing.address.street.0',
                  'Billing Street2:' => 'billing.address.street.1',
                  'Billing City:' => 'billing.address.city',
                  'Billing State/Province:' => 'billing.address.state',
                  'Billing Postal Code:' => 'billing.address.pcode',
                  'Billing Country:' => 'billing.address.country',
                  'Billing Phone:' => 'billing.phone',
                  'Billing FAX:' => 'billing.fax',
                  'Billing Email:' => 'billing.email'
		            );

		$r['regyinfo'] = array(
                          'referrer' => 'http://whois.afilias.info',
                          'registrar' => 'Afilias Global Registry Services'
                          );

		$r['regrinfo'] = generic_parser_b($data_str['rawdata'], $items);
		return $r;
		}

	}

?>
