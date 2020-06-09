<?php
/**
 * @copyright Copyright (c) 2020 Joshua Smith
 * @license   See LICENSE file
 */

namespace phpWhois\Handlers;

require_once __DIR__ . '/../whois.parser.php';

/**
 * AbstractHandler
 */
abstract class AbstractHandler implements HandlerInterface
{
    public $deepWhois;

    /**
     * @param string[] $lines
     *
     * @return string[]
     */
    protected function removeBlankLines(array $lines): array
    {
        return array_filter(
            $lines,
            static function ($s) {
                return !empty($s);
            }
        );
    }

    protected function easy_parser(
        $data_str,
        $items,
        $date_format,
        $translate = [],
        $has_org = false,
        $partial_match = false,
        $def_block = false
    ) {
        return easy_parser(
            $data_str,
            $items,
            $date_format,
            $translate,
            $has_org,
            $partial_match,
            $def_block
        );
    }

    protected function format_dates(&$res, $format = 'mdy')
    {
        return format_dates($res, $format);
    }

    protected function generic_parser_b($rawdata, $items = [], $dateformat = 'mdy', $hasreg = true, $scanall = false)
    {
        return generic_parser_b($rawdata, $items, $dateformat, $hasreg, $scanall);
    }

    protected function get_blocks($rawdata, $items, $partial_match = false, $def_block = false)
    {
        return get_blocks($rawdata, $items, $partial_match, $def_block);
    }

    protected function get_contacts($array, $extra_items = [], $has_org = false)
    {
        return get_contacts($array, $extra_items, $has_org);
    }

    protected function get_contact($array, $extra_items = [], $has_org = false)
    {
        return get_contact($array, $extra_items, $has_org);
    }

    protected function generic_parser_a($rawdata, $translate, $contacts, $main = 'domain', $dateformat = 'dmy')
    {
        return generic_parser_a($rawdata, $translate, $contacts, $main, $dateformat);
    }
}
