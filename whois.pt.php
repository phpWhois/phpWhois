<?php
/* TODO:
   - whois - converter para http://domaininfo.com/idn_conversion.asp punnycode antes de efectuar a pesquisa
   - o punnycode deveria fazer parte dos resultados fazer parte dos resultados!
*/

if (!defined('__PT_HANDLER__'))
	define('__PT_HANDLER__', 1);

require_once('whois.parser.php');

class pt_handler {

	function parse($data, $query)
		{
		$items = array(
					'domain.name' 		=> 'Nome de domínio / Domain Name:',
					'domain.created' 	=> 'Data de registo / Creation Date (dd/mm/yyyy):',
					'domain.nserver.' 	=> 'Nameserver:',
					'domain.status'	 	=> 'Estado / Status:',
					'owner'				=> 'Titular / Registrant',
					'bill'				=> 'Entidade Gestora / Billing Contact',
					'admin'				=> 'Responsável Administrativo / Admin Contact',
					'tech'				=> 'Responsável Técnico / Tech Contact',
					'#'					=> 'Nameserver Information'
					);
					
		$r['regrinfo'] = get_blocks($data['rawdata'], $items);

		if (empty($r['regrinfo']['domain']['name']))
			{
			$r['regrinfo']['registered'] = 'no';
			return;
			}

		$r['regrinfo']['domain']['created'] = get_date($r['regrinfo']['domain']['created'], 'dmy');		
    
		if ($r['regrinfo']['domain']['status'] == 'ACTIVE')
			$r['regrinfo']['registered'] = 'yes';
		else
			$r['regrinfo']['registered'] = 'no';

		if (isset($r['regrinfo']['admin']))
				$r['regrinfo']['admin'] = get_contact($r['regrinfo']['admin']);
				
		if (isset($r['regrinfo']['owner']))
				$r['regrinfo']['owner'] = get_contact($r['regrinfo']['owner']);
				
		if (isset($r['regrinfo']['tech']))
				$r['regrinfo']['tech'] = get_contact($r['regrinfo']['tech']);
				
		if (isset($r['regrinfo']['bill']))
				$r['regrinfo']['bill'] = get_contact($r['regrinfo']['bill']);
						
		$r['regyinfo'] = array(
			'referrer' => 'http://www.fccn.pt',
			'registrar' => 'FCCN'
			);
    
		return $r;
		}
	}
?>
