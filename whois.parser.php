<?php
require_once('getdate.whois');
require_once('genutil.whois');

//-------------------------------------------------------------------------

function generic_parser_a ($rawdata,$translate,$contacts,$main='domain',$dateformat='dmy')
{
$r=array();
$newblock=false;
$hasdata=false;
$block=array();
$gkey="main";
$dend=false;

while (list($key,$val)=each($rawdata))
      { $val=trim($val);
        if ($val!='' && $val[0]=='%')
           { if (!$dend) $disclaimer[]=trim(substr($val,1));
             continue;
           }
	if ($val=='')
           { $newblock=true;
             continue;
           }
	if ($newblock && $hasdata)
           { $blocks[$gkey]=$block;
             $block=array();
	     $gkey="";
           }
        $dend=true;
        $newblock=false;
	$k=trim(strtok($val,':'));
        $v=trim(substr(strstr($val,':'),1));

	if ($v=="") continue;

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

	if ($k=="handle") $gkey=$v;

	if (isset($block[$k]) && is_array($block[$k]))
    	     $block[$k][]=$v;
        else if (!isset($block[$k]) || $block[$k]=='') 
                    $block[$k]=$v;
             else { $x=$block[$k];
		    unset($block[$k]);
		    $block[$k][]=$x;
                    $block[$k][]=$v;
                  }
      }

if ($hasdata) $blocks[$gkey]=$block;

if (isset($disclaimer) && is_array($disclaimer)) 
    $ret['disclaimer']=$disclaimer;

if (!isset($blocks) || !is_array($blocks['main']))
   { $ret['registered']='no';
     return $ret;
   }

$r=$blocks['main'];

$ret['registered']='yes';

while (list($key,$val)=each($contacts))
       if (isset($r[$key]))
	 {
	   if (is_array($r[$key]))
	        $blk=$r[$key][count($r[$key])-1];
	   else $blk=$r[$key];

	   $ret[$val]=$blocks[$blk];
	   unset($r[$key]); 
         }

$ret[$main]=$r;
format_dates($ret,$dateformat);

return $ret;
}

//-------------------------------------------------------------------------

function get_blocks ( $rawdata, $items )
{

$r=array();
$endtag='';

while (list($key,$val)=each($rawdata))
      {
        $val=trim($val);
	if ($val=='') continue;

	$found=false;
	reset($items);

	while (list($field, $match)=each($items)) {

		$pos=strpos($val,$match);

		if ($field!='' && $pos!==false) {
			if ($val==$match) {
				$found=true;
				$endtag='';
				$line=$val;
				break;
				}

			$last=substr($val,-1,1);

			if ($last==':' || $last=='-' || $last==']') {
				$found=true;
				$endtag=$last;
				$line=$val;
				break;
			}
			else {
				$var=getvarname(strtok($field,"#"));
				$itm=trim(substr($val,$pos+strlen($match)));
				eval("\$r".$var."=\$itm;");
			}
		}
	}

	if (!$found) continue;

	$block=array();
	$found=false;
	$spaces=0;

	while (list($key,$val)=each($rawdata))
              { 
		$val=trim($val);
		if ($val=="") { 
                	if ($found && ++$spaces==2) break;	
		     	continue;
                }
                if (!$found) {
			$found=true;
			$block[]=$val;
			continue;
		}
		$last=substr(trim($val),-1,1);
		if ($last==$endtag) {
			prev($rawdata);
			break;
		}
		if ($spaces>0) {
			reset($items);
			$ok=true;
			while (list($field, $match)=each($items)) {
				$pos=strpos($val,$match);
				if ($pos!==false) $ok=false;
			}
			if (!$ok) {
				prev($rawdata);
				break;
			}
		}
		$block[]=$val;
              }

	reset($items);

	while (list($field, $match)=each($items)) {
                $pos=strpos($line,$match);
                if ($pos!==false) {
        		$var=getvarname($field);
        		eval("\$r".$var."=\$block;");
		}
	}
      }

return $r;
}

//-------------------------------------------------------------------------

function get_contact ( $array, $extra_items='' )
{

if (!is_array($array))
	return array();

$items = array (
		'fax..:' => 'fax',
		'fax-' => 'fax',
		'fax:'   => 'fax',
		'[fax]' => 'fax',
		'(fax)' => 'fax',
		'fax' => 'fax',
		'phone:' => 'phone',
		'email:' => 'email',
		'company name:' => 'organization',
		'first name:' => 'name.first',
		'last name:' => 'name.last',
		'street:' => 'address.street',
		'language:' => '',
		'location:' => 'address.city',
		'country:' => 'address.country',
		'name:' => 'name',
		'fax.' => 'fax',
		'tel.' => 'phone'
               );

if ($extra_items!='')
	$items=array_merge($extra_items,$items);

while (list($key,$val)=each($array))
      {
	$ok=true;

	while ($ok)
		{
		reset($items);
		$ok=false;

		while (list($match,$field)=each($items))
	      		{
			$pos=strpos(strtolower($val),$match);
			if ($pos===false) continue;
			$itm=trim(substr($val,$pos+strlen($match)));
			if ($field!='')
				{
				eval("\$r".getvarname($field)."=\$itm;");
				}
			$val=trim(substr($val,0,$pos));

			if ($val=='')
                	     	unset($array[$key]);
		        else
				{
			     	$array[$key]=$val;
				$ok=true;
				}
                	break;
        	      } 	
		}
	if ($val=='') continue;

	if (!preg_match("/[^0-9\(\)\-\.\+ ]/", $val) && strlen($val)>5)
           {
	     if (isset($r['phone']))
	        $r['fax']=$val;	
	     else
		$r['phone']=$val;
	     unset($array[$key]);
	     continue;
	   }

	if (strstr($val,'@'))
           {
	     $val=str_replace("\t",' ',$val);	
	     $parts=explode(' ',$val);
             $top=count($parts)-1;
             $r['email']=str_replace('(','',$parts[$top]);
	     $r['email']=str_replace(')','',$r['email']);
             array_pop($parts);
             $val=implode(' ',$parts);
	     if ($val=='') {
		unset($array[$key]);
		continue;
	     }
             $r['name']=$val;
             unset($array[$key]);
	     if ($key==1)
                {
		  $r['organization']=$array[0];
		  unset($array[0]);
                }
           }
      }     

if (!isset($r['name']) && isset($array[0]))
	{
	$r['name']=$array[0];
	unset($array[0]);
	}

if (isset($r['name']) && is_array($r['name']))
	{
	$r['name']=implode($r['name'],' ');
	}

if (!empty($array) && !isset($r['address']))
	$r['address']=$array;

return $r;
}
?>