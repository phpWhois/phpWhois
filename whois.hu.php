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

/* hunic.whois	0.01	Manuel Machajdik <machajdik@gmxpro.net> */
/* based upon org.whois and atnic.whois */

if(!defined('__HU_HANDLER__'))
  define('__HU_HANDLER__',1);

require_once('whois.parser.php');

class hu_handler {

  function parse ($data_str, $query) {


    $translate = array (
                        'fax-no'		=> 'fax',
                        'e-mail'		=> 'email',
                        'hun-id'		=> 'handle',
                        'person'		=> 'name',
                        'nameserver' 	=> 'nserver',
                        'person'		=> 'name',
                        'org'			=> 'organization',
                        'registered'	=> 'created'
                        );

    $contacts = array (
                        'registrar'		=> 'owner',
                        'admin-c'		=> 'admin',
                        'tech-c'		=> 'tech',
                        'billing-c'		=> 'billing',
                        'zone-c'		=> 'zone',
                        'owner-hun-id'  => 'owner'
                      );
    
    // make those broken hungary comments standards-conforming
	// replace first found hun-id with owner-hun-id (will be parsed later on)
	// make output UTF-8

	$comments = true;
	$owner_id = true;
	
	foreach ($data_str['rawdata'] as $i => $val)
		{
		if ($comments)
			{
			if (strpos($data_str['rawdata'][$i],'domain:') === false)
				{
				if ($i) $data_str['rawdata'][$i] = '% '.$data_str['rawdata'][$i];
				}
			else
				$comments = false;
			}
		else
			if ($owner_id && substr($data_str['rawdata'][$i],0,7) == 'hun-id:')
				{
				$data_str['rawdata'][$i] = 'owner-'.$data_str['rawdata'][$i];
				$owner_id = false;
				}
				
		$data_str['rawdata'][$i] = utf8_encode($data_str['rawdata'][$i]);
		}
		
	$reg = generic_parser_a($data_str['rawdata'],$translate,$contacts);
	
	unset($reg['domain']['organization']);
	unset($reg['domain']['address']);
	unset($reg['domain']['phone']);
	unset($reg['domain']['fax']);

	$r['regrinfo'] = $reg;
	$r['regyinfo'] = array('referrer'=>'http://www.nic.hu','registrar'=>'HUNIC');
	$r['rawdata'] = $data_str['rawdata'];
	format_dates($r,'ymd');
	return($r);
	}
}
