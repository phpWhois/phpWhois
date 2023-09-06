<?php

/**
 * @license   See LICENSE file
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

class AppHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        return [
            'regrinfo' => static::generic_parser_b($data_str['rawdata']),
            'rawdata'  => $data_str['rawdata'],
        ];
    }
}
