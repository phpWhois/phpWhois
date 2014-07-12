<?php
/*
Whois.php        PHP classes to conduct whois queries

Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic

Maintained by David Saez

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

//-------------------------------------------------------------------------

function generic_parser_a ($rawdata, $translate, $contacts, $main='domain', $dateformat='dmy')
{
$blocks = generic_parser_a_blocks($rawdata,$translate,$disclaimer);

if (isset($disclaimer) && is_array($disclaimer))
    $ret['disclaimer']=$disclaimer;

if (empty($blocks) || !is_array($blocks['main']))
   {
   $ret['registered']='no';
   return $ret;
   }

$r = $blocks['main'];
$ret['registered'] = 'yes';

while (list($key,$val) = each($contacts))
	if (isset($r[$key]))
		{
		if (is_array($r[$key]))
	        $blk = $r[$key][count($r[$key])-1];
		else
			$blk = $r[$key];

		$blk = strtoupper(strtok($blk,' '));
		if (isset($blocks[$blk])) $ret[$val] = $blocks[$blk];
		unset($r[$key]);
		}

if ($main) $ret[$main] = $r;

format_dates($ret,$dateformat);
return $ret;
}

//-------------------------------------------------------------------------

function generic_parser_a_blocks ($rawdata, $translate, &$disclaimer)
{
$r = array();
$newblock = false;
$hasdata = false;
$block = array();
$blocks = false;
$gkey = 'main';
$dend = false;

while (list($key,$val)=each($rawdata))
	{
	$val=trim($val);

	if ($val != '' && ($val[0] == '%' || $val[0] == '#'))
		{
		if (!$dend) $disclaimer[]=trim(substr($val,1));
		continue;
		}
	if ($val=='')
		{
		$newblock = true;
		continue;
		}
	if ($newblock && $hasdata)
		{
		$blocks[$gkey] = $block;
		$block = array();
		$gkey = '';
		}
	$dend = true;
	$newblock = false;
	$k = trim(strtok($val,':'));
	$v = trim(substr(strstr($val,':'),1));

	if ($v == '') continue;

	$hasdata = true;

	if (isset($translate[$k]))
		{
		$k=$translate[$k];
		if ($k=='') continue;
		if (strstr($k,'.'))
			{
			eval("\$block".getvarname($k)."=\$v;");
			continue;
			}
           }
	else $k=strtolower($k);

	if ($k=='handle')
		{
		$v = strtok($v,' ');
		$gkey = strtoupper($v);
		}

	if (isset($block[$k]) && is_array($block[$k]))
		$block[$k][]=$v;
	else
		if (!isset($block[$k]) || $block[$k]=='')
			$block[$k]=$v;
		else
			{
			$x=$block[$k];
		    unset($block[$k]);
		    $block[$k][]=$x;
            $block[$k][]=$v;
            }
	}

if ($hasdata) $blocks[$gkey]=$block;

return $blocks;
}

//-------------------------------------------------------------------------

function generic_parser_b ( $rawdata, $items = false, $dateformat='mdy', $hasreg=true, $scanall=false )
{
if (!$items)
	$items = array(
				'Domain Name:' => 'domain.name',
				'Domain ID:' => 'domain.handle',
				'Sponsoring Registrar:' => 'domain.sponsor',
				'Registrar ID:' => 'domain.sponsor',
				'Domain Status:' => 'domain.status.',
				'Status:' => 'domain.status.',
				'Name Server:' => 'domain.nserver.',
				'Nameservers:' => 'domain.nserver.',
				'Maintainer:' => 'domain.referer',
				 
				'Domain Registration Date:' => 'domain.created',
				'Domain Create Date:' => 'domain.created',
				'Domain Expiration Date:' => 'domain.expires',
				'Domain Last Updated Date:' => 'domain.changed',
				'Creation Date:' => 'domain.created',
				'Last Modification Date:' => 'domain.changed',
				'Expiration Date:' => 'domain.expires',
				'Created On:' => 'domain.created',
                'Last Updated On:' => 'domain.changed',
                'Expiration Date:' => 'domain.expires',
				 
				'Registrant ID:' => 'owner.handle',
				'Registrant Name:' => 'owner.name',
				'Registrant Organization:' => 'owner.organization',
				'Registrant Address:' => 'owner.address.street.',
				'Registrant Address1:' => 'owner.address.street.',
				'Registrant Address2:' => 'owner.address.street.',
				'Registrant Street:' => 'owner.address.street.',
				'Registrant Street1:' => 'owner.address.street.',
				'Registrant Street2:' => 'owner.address.street.',
				'Registrant Street3:' => 'owner.address.street.',
				'Registrant Postal Code:' => 'owner.address.pcode',
				'Registrant City:' => 'owner.address.city',
				'Registrant State/Province:' => 'owner.address.state',
				'Registrant Country:' => 'owner.address.country',
				'Registrant Country/Economy:' => 'owner.address.country',
				'Registrant Phone Number:' => 'owner.phone',
				'Registrant Phone:' => 'owner.phone',
				'Registrant Facsimile Number:' => 'owner.fax',
				'Registrant FAX:' => 'owner.fax',
				'Registrant Email:' => 'owner.email',
				'Registrant E-mail:' => 'owner.email',

				'Administrative Contact ID:' => 'admin.handle',
				'Administrative Contact Name:' => 'admin.name',
				'Administrative Contact Organization:' => 'admin.organization',
				'Administrative Contact Address:' => 'admin.address.street.',
				'Administrative Contact Address1:' => 'admin.address.street.',
				'Administrative Contact Address2:' => 'admin.address.street.',
				'Administrative Contact Postal Code:' => 'admin.address.pcode',
				'Administrative Contact City:' => 'admin.address.city',
				'Administrative Contact State/Province:' => 'admin.address.state',
				'Administrative Contact Country:' => 'admin.address.country',
				'Administrative Contact Phone Number:' => 'admin.phone',
				'Administrative Contact Email:' => 'admin.email',
				'Administrative Contact Facsimile Number:' => 'admin.fax',
				'Administrative Contact Tel:' => 'admin.phone',
				'Administrative Contact Fax:' => 'admin.fax',
				'Administrative ID:' => 'admin.handle',
				'Administrative Name:' => 'admin.name',
				'Administrative Organization:' => 'admin.organization',
				'Administrative Address:' => 'admin.address.street.',
				'Administrative Address1:' => 'admin.address.street.',
				'Administrative Address2:' => 'admin.address.street.',
				'Administrative Postal Code:' => 'admin.address.pcode',
				'Administrative City:' => 'admin.address.city',
				'Administrative State/Province:' => 'admin.address.state',
				'Administrative Country/Economy:' => 'admin.address.country',
				'Administrative Phone:' => 'admin.phone',
				'Administrative E-mail:' => 'admin.email',
				'Administrative Facsimile Number:' => 'admin.fax',
				'Administrative Tel:' => 'admin.phone',
				'Administrative FAX:' => 'admin.fax',
				'Admin ID:' => 'admin.handle',
				'Admin Name:' => 'admin.name',
				'Admin Organization:' => 'admin.organization',
				'Admin Street:' => 'admin.address.street.',
				'Admin Street1:' => 'admin.address.street.',
				'Admin Street2:' => 'admin.address.street.',
				'Admin Street3:' => 'admin.address.street.',
				'Admin Address:' => 'admin.address.street.',
				'Admin Address2:' => 'admin.address.street.',
				'Admin Address3:' => 'admin.address.street.',
				'Admin City:' => 'admin.address.city',
				'Admin State/Province:' => 'admin.address.state',
				'Admin Postal Code:' => 'admin.address.pcode',
				'Admin Country:' => 'admin.address.country',
				'Admin Country/Economy:' => 'admin.address.country',
				'Admin Phone:' => 'admin.phone',
				'Admin FAX:' => 'admin.fax',
				'Admin Email:' => 'admin.email',
				'Admin E-mail:' => 'admin.email',

				'Technical Contact ID:' => 'tech.handle',
				'Technical Contact Name:' => 'tech.name',
				'Technical Contact Organization:' => 'tech.organization',
				'Technical Contact Address:' => 'tech.address.street.',
				'Technical Contact Address1:' => 'tech.address.street.',
				'Technical Contact Address2:' => 'tech.address.street.',
				'Technical Contact Postal Code:' => 'tech.address.pcode',
				'Technical Contact City:' => 'tech.address.city',
				'Technical Contact State/Province:' => 'tech.address.state',
				'Technical Contact Country:' => 'tech.address.country',
				'Technical Contact Phone Number:' => 'tech.phone',
				'Technical Contact Facsimile Number:' => 'tech.fax',
				'Technical Contact Phone:' => 'tech.phone',
				'Technical Contact Fax:' => 'tech.fax',
				'Technical Contact Email:' => 'tech.email',
				'Technical ID:' => 'tech.handle',
				'Technical Name:' => 'tech.name',
				'Technical Organization:' => 'tech.organization',
				'Technical Address:' => 'tech.address.street.',
				'Technical Address1:' => 'tech.address.street.',
				'Technical Address2:' => 'tech.address.street.',
				'Technical Postal Code:' => 'tech.address.pcode',
				'Technical City:' => 'tech.address.city',
				'Technical State/Province:' => 'tech.address.state',
				'Technical Country/Economy:' => 'tech.address.country',
				'Technical Phone Number:' => 'tech.phone',
				'Technical Facsimile Number:' => 'tech.fax',
				'Technical Phone:' => 'tech.phone',
				'Technical Fax:' => 'tech.fax',
				'Technical FAX:' => 'tech.fax',
				'Technical E-mail:' => 'tech.email',
				'Tech ID:' => 'tech.handle',
				'Tech Name:' => 'tech.name',
				'Tech Organization:' => 'tech.organization',
				'Tech Address:' => 'tech.address.street.',
				'Tech Address2:' => 'tech.address.street.',
				'Tech Address3:' => 'tech.address.street.',
				'Tech Street:' => 'tech.address.street.',
				'Tech Street1:' => 'tech.address.street.',
				'Tech Street2:' => 'tech.address.street.',
				'Tech Street3:' => 'tech.address.street.',
				'Tech City:' => 'tech.address.city',
				'Tech Postal Code:' => 'tech.address.pcode',
				'Tech State/Province:' => 'tech.address.state',
				'Tech Country:' => 'tech.address.country',
				'Tech Country/Economy:' => 'tech.address.country',
				'Tech Phone:' => 'tech.phone',
				'Tech FAX:' => 'tech.fax',
				'Tech Email:' => 'tech.email',
				'Tech E-mail:' => 'tech.email',

				'Billing Contact ID:' => 'billing.handle',
				'Billing Contact Name:' => 'billing.name',
				'Billing Contact Organization:' => 'billing.organization',
				'Billing Contact Address1:' => 'billing.address.street.',
				'Billing Contact Address2:' => 'billing.address.street.',
				'Billing Contact Postal Code:' => 'billing.address.pcode',
				'Billing Contact City:' => 'billing.address.city',
				'Billing Contact State/Province:' => 'billing.address.state',
				'Billing Contact Country:' => 'billing.address.country',
				'Billing Contact Phone Number:' => 'billing.phone',
				'Billing Contact Facsimile Number:' => 'billing.fax',
				'Billing Contact Email:' => 'billing.email',
				'Billing ID:' => 'billing.handle',
				'Billing Name:' => 'billing.name',
				'Billing Organization:' => 'billing.organization',
				'Billing Address:' => 'billing.address.street.',
				'Billing Address1:' => 'billing.address.street.',
				'Billing Address2:' => 'billing.address.street.',
				'Billing Address3:' => 'billing.address.street.',
				'Billing Street:' => 'billing.address.street.',
				'Billing Street1:' => 'billing.address.street.',
				'Billing Street2:' => 'billing.address.street.',
				'Billing Street3:' => 'billing.address.street.',
				'Billing City:' => 'billing.address.city',
				'Billing Postal Code:' => 'billing.address.pcode',
				'Billing State/Province:' => 'billing.address.state',
				'Billing Country:' => 'billing.address.country',
				'Billing Country/Economy:' => 'billing.address.country',
				'Billing Phone:' => 'billing.phone',
				'Billing Fax:' => 'billing.fax',
				'Billing FAX:' => 'billing.fax',
				'Billing Email:' => 'billing.email',
				'Billing E-mail:' => 'billing.email',
				
				'Zone ID:' => 'zone.handle',
                'Zone Organization:' => 'zone.organization',
                'Zone Name:' => 'zone.name',
                'Zone Address:' => 'zone.address.street.',
                'Zone Address 2:' => 'zone.address.street.',
                'Zone City:' => 'zone.address.city',
                'Zone State/Province:' => 'zone.address.state',
                'Zone Postal Code:' => 'zone.address.pcode',
                'Zone Country:' => 'zone.address.country',
                'Zone Phone Number:' => 'zone.phone',
                'Zone Fax Number:' => 'zone.fax',
                'Zone Email:' => 'zone.email'
		            );

$r = '';
$disok = true;

while (list($key,$val) = each($rawdata))
	{
	if (trim($val) != '')
		{
	     if (($val[0]=='%' || $val[0]=='#') && $disok)
			{
			$r['disclaimer'][] = trim(substr($val,1));
			$disok = true;
			continue;
			}

		$disok = false;
		reset($items);

		while (list($match, $field)=each($items))
			{
			$pos = strpos($val,$match);

			if ($pos !== false)
				{
				if ($field != '')
					{
					$var = '$r'.getvarname($field);
					$itm = trim(substr($val,$pos+strlen($match)));

					if ($itm!='')
						eval($var.'="'.str_replace('"','\"',$itm).'";');
					}

				if (!$scanall)
					break;
				}
			}
		}
	}

if (empty($r))
	{
	if ($hasreg) $r['registered'] = 'no';
	}
else
	{
	if ($hasreg) $r['registered'] = 'yes';

	$r = format_dates($r, $dateformat);
	}

return $r;
}

//-------------------------------------------------------------------------

function getvarname ( $vdef )
{
$parts = explode('.',$vdef);
$var = '';

foreach($parts as $mn)
	if ($mn == '') $var = $var.'[]';
	else $var = $var.'["'.$mn.'"]';

return $var;
}

//-------------------------------------------------------------------------

function get_blocks ( $rawdata, $items, $partial_match = false, $def_block = false )
{

$r = array();
$endtag = '';

while (list($key,$val) = each($rawdata))
	{
	$val = trim($val);
	if ($val == '') continue;

	$var = $found = false;

	foreach ($items as $field => $match)
		{
		$pos = strpos($val,$match);

		if ($field != '' && $pos !== false)
			{
			if ($val == $match)
				{
				$found = true;
				$endtag = '';
				$line = $val;
				break;
				}

			$last = substr($val,-1,1);

			if ($last == ':' || $last == '-' || $last == ']')
				{
				$found = true;
				$endtag = $last;
				$line = $val;
				}
			else
				{
				$var = getvarname(strtok($field,'#'));
				$itm = trim(substr($val,$pos+strlen($match)));
				eval('$r'.$var.'=$itm;');
				}

			break;
			}
		}

	if (!$found)
		{
		if (!$var && $def_block) $r[$def_block][] = $val;
		continue;
		}

	$block = array();

	// Block found, get data ...

	while (list($key,$val) = each($rawdata))
		{
		$val = trim($val);

		if ($val == '' || $val == str_repeat($val[0],strlen($val))) continue;

		$last = substr($val,-1,1);
/*
		if ($last == $endtag)
			{
			// Another block found
			prev($rawdata);
			break;
			}

		if ($endtag == '' || $partial_match)
		*/
		if ($endtag == '' || $partial_match || $last == $endtag)
			{
			//Check if this line starts another block
			$et = false;

			foreach ($items as $field => $match)
				{
				$pos = strpos($val,$match);

				if ($pos !== false && $pos == 0)
					{
					$et = true;
					break;
					}
				}

			if ($et)
				{
				// Another block found
				prev($rawdata);
				break;
				}
			}

		$block[] = $val;
		}

	if (empty($block)) continue;

	foreach ($items as $field => $match)
		{
		$pos = strpos($line,$match);

		if ($pos !== false)
			{
			$var = getvarname(strtok($field,'#'));
			if ($var != '[]') eval('$r'.$var.'=$block;');
			}
		}
	}

return $r;
}

//-------------------------------------------------------------------------

function easy_parser($data_str, $items, $date_format, $translate = false ,
					 $has_org = false, $partial_match = false,
					 $def_block = false )
{
$r = get_blocks($data_str, $items, $partial_match, $def_block);
$r = get_contacts($r, $translate, $has_org);
format_dates($r, $date_format);
return $r;
}

//-------------------------------------------------------------------------

function get_contacts ( $array, $extra_items='', $has_org= false )
{
if (isset($array['billing']))
	$array['billing'] = get_contact($array['billing'], $extra_items, $has_org);

if (isset($array['tech']))
	$array['tech'] = get_contact($array['tech'], $extra_items, $has_org);

if (isset($array['zone']))
	$array['zone'] = get_contact($array['zone'], $extra_items, $has_org);
			
if (isset($array['admin']))
	$array['admin'] = get_contact($array['admin'], $extra_items, $has_org);
		
if (isset($array['owner']))
	$array['owner'] = get_contact($array['owner'], $extra_items, $has_org);

if (isset($array['registrar']))
	$array['registrar'] = get_contact($array['registrar'], $extra_items, $has_org);

return $array;
}

//-------------------------------------------------------------------------

function get_contact ( $array, $extra_items='', $has_org= false )
{

if (!is_array($array))
	return array();

$items = array (
		'fax..:' => 'fax',
		'fax.' => 'fax',
		'fax-no:' => 'fax',
		'fax -' => 'fax',
		'fax-' => 'fax',
		'fax::'   => 'fax',
		'fax:'   => 'fax',
		'[fax]' => 'fax',
		'(fax)' => 'fax',
		'fax' => 'fax',
		'tel. ' => 'phone',
		'tel:' => 'phone',
		'phone::' => 'phone',
		'phone:' => 'phone',
		'phone-' => 'phone',
		'phone -' => 'phone',
		'email:' => 'email',
		'e-mail:' => 'email',
		'company name:' => 'organization',
		'organisation:' => 'organization',
		'first name:' => 'name.first',
		'last name:' => 'name.last',
		'street:' => 'address.street',
		'address:' => 'address.street.',
		'language:' => '',
		'location:' => 'address.city',
		'country:' => 'address.country',
		'name:' => 'name',
		'last modified:' => 'changed'
		);

if ($extra_items)
	{
	foreach($items as $match => $field)
		if (!isset($extra_items[$match]))
		$extra_items[$match] = $field;
	$items = $extra_items;
	}

while (list($key,$val)=each($array))
	{
	$ok=true;

	while ($ok)
		{
		reset($items);
		$ok = false;

		while (list($match,$field) = each($items))
			{
			$pos = strpos(strtolower($val),$match);

			if ($pos === false) continue;

			$itm = trim(substr($val,$pos+strlen($match)));

			if ($field != '' && $itm != '')
				{
				eval('$r'.getvarname($field).'=$itm;');
				}

			$val = trim(substr($val,0,$pos));

			if ($val == '')
				{
				unset($array[$key]);
				break;
				}
			else
				{
				$array[$key] = $val;
				$ok = true;
				}
			//break;
			}

		if (preg_match("/([+]*[-\(\)\. x0-9]){7,}/", $val, $matches))
			{
			$phone = trim(str_replace(' ','',$matches[0]));

			if (strlen($phone) > 8 && !preg_match('/[0-9]{5}\-[0-9]{3}/',$phone))
				{
				if (isset($r['phone']))
					{
					if (isset($r['fax'])) continue;
					$r['fax'] = trim($matches[0]);
					}
				else
					{
					$r['phone'] = trim($matches[0]);
					}

				$val = str_replace($matches[0],'',$val);

				if ($val == '')
					{
					unset($array[$key]);
					continue;
					}
				else
					{
					$array[$key] = $val;
					$ok = true;
					}
				}
			}

		if (preg_match('/([-0-9a-zA-Z._+&\/=]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6})/',$val, $matches))
			{
			$r['email'] = $matches[0];

			$val = str_replace($matches[0],'',$val);
			$val = trim(str_replace('()','',$val));

			if ($val == '')
				{
				unset($array[$key]);
				continue;
				}
			else
				{
				if (!isset($r['name']))
					{
					$r['name'] = $val;
					unset($array[$key]);
					}
				else
					$array[$key] = $val;

				$ok = true;
				}
			}
		}
	}

if (!isset($r['name']) && count($array)>0)
	{
	$r['name'] = array_shift($array);
	}

if ($has_org && count($array)>0)
	{
	$r['organization'] = array_shift($array);
	}

if (isset($r['name']) && is_array($r['name']))
	{
	$r['name'] = implode($r['name'],' ');
	}

if (!empty($array))
	{
	if (isset($r['address']))
		$r['address'] = array_merge($r['address'],$array);
	else
		$r['address'] = $array;
	}

return $r;
}

//-------------------------------------------------------------------------

function format_dates (&$res,$format='mdy')
{
if (!is_array($res)) return $res;

foreach ($res as $key => $val)
	{
	if (is_array($val))
		{
		if (!is_numeric($key) && ($key=='expires' || $key=='created' || $key=='changed'))
			{
			$d = get_date($val[0],$format);
			if ($d) $res[$key] = $d;
			}
		else
			{
			$res[$key] = format_dates($val,$format);
			}
		}
	else
		{
		if (!is_numeric($key) && ($key=='expires' || $key=='created' || $key=='changed'))
			{
			$d = get_date($val,$format);
			if ($d) $res[$key] = $d;
			}
		}
	}

return $res;
}

//-------------------------------------------------------------------------

function get_date($date,$format)
{
$months = array( 'jan'=>1,  'ene'=>1,  'feb'=>2,  'mar'=>3, 'apr'=>4, 'abr'=>4,
                 'may'=>5,  'jun'=>6,  'jul'=>7,  'aug'=>8, 'ago'=>8, 'sep'=>9,
                 'oct'=>10, 'nov'=>11, 'dec'=>12, 'dic'=>12 );

$parts = explode(' ',$date);

if (strpos($parts[0],'@') !== false)
	{
	unset($parts[0]);
	$date = implode(' ',$parts);
	}

$date = str_replace(',',' ',trim($date));
$date = str_replace('.',' ',$date);
$date = str_replace('-',' ',$date);
$date = str_replace('/',' ',$date);
$date = str_replace("\t",' ',$date);

$parts = explode(' ',$date);
$res = false;

if ((strlen($parts[0]) == 8 || count($parts) == 1) && is_numeric($parts[0]))
	{
	$val = $parts[0];
	for ($p=$i=0; $i<3; $i++)
		{
		if ($format[$i] != 'Y')
			{
			$res[$format[$i]] = substr($val,$p,2);
			$p += 2;
			}
		else
			{
			$res['y'] = substr($val,$p,4);
			$p += 4;
			}
		}
	}
else
	{
	$format = strtolower($format);

	for ($p=$i=0; $p<count($parts) && $i<strlen($format); $p++)
		{
		if (trim($parts[$p]) == '')
			continue;

		if ($format[$i] != '-')
			{
			$res[$format[$i]] = $parts[$p];
			}
		$i++;
		}
	}

if (!$res) return $date;

$ok = false;

while (!$ok)
	{
	reset($res);
	$ok = true;

	while (list($key, $val) = each($res))
		{
		if ($val == '' || $key == '') continue;

		if (!is_numeric($val) && isset($months[substr(strtolower($val),0,3)]))
			{
			$res[$key] = $res['m'];
			$res['m'] = $months[substr(strtolower($val),0,3)];
			$ok = false;
			break;
			}

		if ($key != 'y' && $key != 'Y' && $val > 1900)
			{
			$res[$key] = $res['y'];
			$res['y'] = $val;
			$ok = false;
			break;
			}
		}
	}

if ($res['m'] > 12)
	{
	$v = $res['m'];
	$res['m'] = $res['d'];
	$res['d'] = $v;
	}

if ($res['y'] < 70)
	$res['y'] += 2000;
else
	if ($res['y'] <= 99)
		$res['y'] += 1900;

return sprintf("%.4d-%02d-%02d",$res['y'],$res['m'],$res['d']);
}

?>