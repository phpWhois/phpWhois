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

$r=$blocks['main'];

$ret['registered']='yes';

while (list($key,$val)=each($contacts))
	if (isset($r[$key]))
		{
		if (is_array($r[$key]))
	        $blk=$r[$key][count($r[$key])-1];
		else
			$blk=$r[$key];

		$blk = strtoupper(strtok($blk,' '));
		$ret[$val]=$blocks[$blk];
		unset($r[$key]); 
		}

if ($main)
	$ret[$main]=$r;
	
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
		$newblock=true;
		continue;
		}
	if ($newblock && $hasdata)
		{
		$blocks[$gkey]=$block;
		$block=array();
		$gkey='';
		}
	$dend=true;
	$newblock=false;
	$k=trim(strtok($val,':'));
	$v=trim(substr(strstr($val,':'),1));

	if ($v=='') continue;

	$hasdata=true;

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

function generic_parser_b ( $rawdata, $items, $dateformat='mdy', $hasreg=true, $scanall=false )

{
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
				if ($field!='')
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
$parts=explode(".",$vdef);
$var="";

while (list($fn,$mn)=each($parts))
       if ($mn=="")
            $var=$var."[]";
       else $var=$var."[\"".$mn."\"]";

return $var;
}

//-------------------------------------------------------------------------

function get_blocks ( $rawdata, $items, $partial_match = false )
{

$r = array();
$endtag='';

while (list($key,$val) = each($rawdata))
	{
	$val = trim($val);
	if ($val == '') continue;

	$found = false;
	reset($items);

	while (list($field, $match) = each($items)) {

		$pos = strpos($val,$match);

		if ($field != '' && $pos !== false) {

			if ($val == $match) {
				$found = true;
				$endtag = '';
				$line = $val;
				break;
				}

			$last = substr($val,-1,1);

			if ($last == ':' || $last == '-' || $last == ']') {
				$found = true;
				$endtag = $last;
				$line = $val;
				break;
			}
			else {
				$var = getvarname(strtok($field,'#'));
				$itm = trim(substr($val,$pos+strlen($match)));
				eval('$r'.$var.'=$itm;');
			}
		}
	}

	if (!$found) continue;

	$block = array();

	// Block found, get data ...
	
	while (list($key,$val) = each($rawdata))
		{ 
		$val = trim($val);
		
		if ($val == '' || $val == str_repeat($val[0],strlen($val))) continue;

		$last = substr($val,-1,1);

		if ($last == $endtag) {
			// Another block found
			prev($rawdata);
			break;
			}
			
		if ($endtag == '' || $partial_match)
			{
			//Check if this line starts another block
			reset($items);
			$et = false;
			
			while (list($field, $match) = each($items)) 
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
			
		$block[]=$val;
		}

	reset($items);

	if (empty($block)) continue;
	
	while (list($field, $match)=each($items)) {
		$pos=strpos($line,$match);
		if ($pos!==false) {
			$var=getvarname(strtok($field,'#'));
			eval('$r'.$var.'=$block;');
			}
		}
	}

return $r;
}

//-------------------------------------------------------------------------

function get_contact ( $array, $extra_items='', $has_org= false )
{

if (!is_array($array))
	return array();

$items = array (		
		'fax..:' => 'fax',
		'fax.' => 'fax',
		'fax -' => 'fax',
		'fax-' => 'fax',
		'fax::'   => 'fax',
		'fax:'   => 'fax',
		'[fax]' => 'fax',
		'(fax)' => 'fax',
		'fax' => 'fax',
		'tel.' => 'phone',
		'tel:' => 'phone',
		'phone::' => 'phone',
		'phone:' => 'phone',
		'phone-' => 'phone',
		'phone -' => 'phone',
		'email:' => 'email',
		'e-mail:' => 'email',
		'company name:' => 'organization',
		'first name:' => 'name.first',
		'last name:' => 'name.last',
		'street:' => 'address.street',
		'language:' => '',
		'location:' => 'address.city',
		'country:' => 'address.country',
		'name:' => 'name'				
		);

if ($extra_items)
	$items = array_merge($extra_items,$items);

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

		if (preg_match("/([+]*[-0-9\(\)\. x]){7,}/", $val, $matches))
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
		$r['address'] = array_merge($array,$r['address']);
	else
		$r['address'] = $array;
	}

return $r;
}

//-------------------------------------------------------------------------

function format_dates (&$res,$format='mdy')
{
if (!is_array($res)) return $res;

reset($res);

while (list($key, $val) = each($res))
	{
	if (is_array($val))
		{
		if (!is_numeric($key) && ($key=='expires' || $key=='created' || $key=='changed'))
			{
			$res[$key]=get_date($val[0],$format);
			}
		else
			{
			$res[$key]=format_dates($val,$format);
			}
		}
	else
		{
		if (!is_numeric($key) && ($key=='expires' || $key=='created' || $key=='changed'))
			{
			$res[$key]=get_date($val,$format);			
			}
		}
	}

return $res;
}

//-------------------------------------------------------------------------

function get_date($date,$format)
{
$months = array( 'jan'=>1, 'feb'=>2, 'mar'=>3, 'apr'=>4,  'may'=>5,  'jun'=>6, 
                 'jul'=>7, 'aug'=>8, 'sep'=>9, 'oct'=>10, 'nov'=>11, 'dec'=>12 );

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
