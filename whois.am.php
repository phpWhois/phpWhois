<?php
if (!defined('__AM_HANDLER__'))
	define('__AM_HANDLER__', 1);

require_once('whois.parser.php');

class am_handler
	{
	function parse($data_str, $query)
		{
		$items = array(
                'owner' 			=> 'Registrant:',
                'domain.name' 		=> 'Domain name:',
                'domain.created' 	=> 'Registered:',
                'domain.changed' 	=> 'Last modified:',
                'domain.nserver' 	=> 'DNS servers:',
                'domain.status'		=> 'Status:',
                'tech' 				=> 'Technical contact:',
                'admin'				=> 'Administrative contact:',
		            );

		$r['regrinfo'] = get_blocks($data_str['rawdata'], $items);

		if (!empty($r['regrinfo']['domain']['name']))
			{
			$r['regrinfo'] = get_contacts($r['regrinfo']);
			$r['regrinfo']['registered'] = 'yes';
			}
		else
			{
			$r = '';
			$r['regrinfo']['registered'] = 'no';
			}

		$r['regyinfo'] = array(
                    'referrer' => 'http://www.isoc.am',
                    'registrar' => 'ISOCAM'
                    );

		return $r;
		}
	}
