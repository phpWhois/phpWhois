<?php

/**
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @license
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @link http://phpwhois.pw
 * @copyright Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic
 * @copyright Maintained by David Saez
 * @copyright Copyright (c) 2014 Dmitry Lukashin
 */

use phpWhois\Handlers\AbstractHandler;

/**
 * @deprecated Use AbstractHandler::generic_parser_a
 */
function generic_parser_a($rawdata, $translate, $contacts, $main = 'domain', $dateformat = 'dmy')
{
    return AbstractHandler::generic_parser_a($rawdata,$translate,$contacts,$main,$dateformat);
}

/**
 * @deprecated Use AbstractHandler::generic_parser_b
 */
function generic_parser_b($rawdata, $items = array(), $dateformat = 'mdy', $hasreg = true, $scanall = false)
{
    return AbstractHandler::generic_parser_b($rawdata, $items, $dateformat, $hasreg, $scanall);
}

/**
 * @param array    $array The array to populate
 * @param string[] $parts
 * @param mixed    $value The value to be assigned to the $vDef key
 *
 * @return array The updated array
 * @see https://github.com/sparc/phpWhois.org/compare/18849d1a98b992190612cdb2561e7b4492c505f5...8c6a18686775b25f05592dd67d7706e47167a498#diff-b8adbe1292f8abca1f943aa844db52aa Original fix by David Saez PAdros sparc
 */
function assign_recursive(array $array, array $parts, $value)
{
    $key = array_shift($parts);

    if (count($parts) === 0) {
        if (!$key) {
            $array[] = $value;
        } else {
            $array[$key] = $value;
        }
    } else {
        if (!isset($array[$key])) {
            $array[$key] = [];
        }
        $array[$key] = assign_recursive($array[$key], $parts, $value);
    }

    return $array;
}

/**
 * @param array  $array The array to populate
 * @param string $vDef  A period-separated string of nested array keys
 * @param mixed  $value The value to be assigned to the $vDef key
 *
 * @return array The updated array
 * @see https://github.com/sparc/phpWhois.org/compare/18849d1a98b992190612cdb2561e7b4492c505f5...8c6a18686775b25f05592dd67d7706e47167a498#diff-b8adbe1292f8abca1f943aa844db52aa Original fix by David Saez PAdros sparc
 */
function assign(array $array, string $vDef, $value)
{
    return assign_recursive($array, explode('.', $vDef), $value);
}

/**
 * @deprecated Use AbstractHandler::get_blocks
 */
function get_blocks($rawdata, $items, $partial_match = false, $def_block = false)
{
    return AbstractHandler::getBlocks($rawdata, $items, $partial_match, $def_block);
}

/**
 * @deprecated Use AbstractHandler::easyParser
 */
function easy_parser($data_str, $items, $date_format, $translate = array(), $has_org = false, $partial_match = false, $def_block = false)
{
    return AbstractHandler::easyParser($data_str, $items, $date_format, $translate, $has_org, $partial_match, $def_block);
}

/**
 * @deprecated Use AbstractHandler::getContacts
 */
function get_contacts($array, $extra_items = array(), $has_org = false)
{
    return AbstractHandler::getContacts($array,$extra_items,$has_org);
}

/**
 * @deprecated Use AbstractHandler::getContact
 */
function get_contact($array, $extra_items = array(), $has_org = false)
{
    return AbstractHandler::getContact($array,$extra_items,$has_org);
}

/**
 * @deprecated Use AbstractHandler::formatDates
 */
function format_dates(&$res, $format = 'mdy')
{
    return AbstractHandler::formatDates($res,$format);
}
