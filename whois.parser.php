<?php
require_once('getdate.whois');
require_once('genutil.whois');

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

?>