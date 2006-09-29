<?php
if (!defined('__PSIUSA_HANDLER__'))
	define('__PSIUSA_HANDLER__', 1);

require_once('whois.parser.php');

class psiusa_handler
	{

	function parse($data_str, $query)
		{

		$items = array(
                  'created:' => 'domain.created',
                  'last-changed:' => 'domain.changed',
                  'status:' => 'domain.status',
                  '[owner-c] fname:' => 'owner.name.first',
                  '[owner-c] lname:' => 'owner.name.last',
                  '[owner-c] org:' => 'owner.organization',
                  '[owner-c] address:' => 'owner.address.street',
                  '[owner-c] city:' => 'owner.address.city',
                  '[owner-c] pcode:' => 'owner.address.pcode',
                  '[owner-c] country:' => 'owner.address.country',
                  '[owner-c] state:' => 'owner.address.state',
                  '[owner-c] phone:' => 'owner.phone',
                  '[owner-c] fax:' => 'owner.fax',
                  '[owner-c] email:' => 'owner.email',
                  '[admin-c] fname:' => 'admin.name.first',
                  '[admin-c] lname:' => 'admin.name.last',
                  '[admin-c] org:' => 'admin.organization',
                  '[admin-c] address:' => 'admin.address.street',
                  '[admin-c] city:' => 'admin.address.city',
                  '[admin-c] pcode:' => 'admin.address.pcode',
                  '[admin-c] country:' => 'admin.address.country',
                  '[admin-c] state:' => 'admin.address.state',
                  '[admin-c] phone:' => 'admin.phone',
                  '[admin-c] fax:' => 'admin.fax',
                  '[admin-c] email:' => 'admin.email',
                  '[tech-c] fname:' => 'tech.name.first',
                  '[tech-c] lname:' => 'tech.name.last',
                  '[tech-c] org:' => 'tech.organization',
                  '[tech-c] address:' => 'tech.address.street',
                  '[tech-c] city:' => 'tech.address.city',
                  '[tech-c] pcode:' => 'tech.address.pcode',
                  '[tech-c] country:' => 'tech.address.country',
                  '[tech-c] state:' => 'tech.address.state',
                  '[tech-c] phone:' => 'tech.phone',
                  '[tech-c] fax:' => 'tech.fax',
                  '[tech-c] email:' => 'tech.email',
                  '[zone-c] fname:' => 'zone.name.first',
                  '[zone-c] lname:' => 'zone.name.last',
                  '[zone-c] org:' => 'zone.organization',
                  '[zone-c] address:' => 'zone.address.street',
                  '[zone-c] city:' => 'zone.address.city',
                  '[zone-c] pcode:' => 'zone.address.pcode',
                  '[zone-c] country:' => 'zone.address.country',
                  '[zone-c] state:' => 'zone.address.state',
                  '[zone-c] phone:' => 'zone.phone',
                  '[zone-c] fax:' => 'zone.fax',
                  '[zone-c] email:' => 'zone.email',
		              );

		$r = generic_parser_b($data_str, $items);
		return ($r);
		}
	}
?>
