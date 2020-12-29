<?php
/**
 * @license   See LICENSE file
 * @copyright Copyright (c) 2020 Joshua Smith
 */

namespace phpWhois\Handlers;

class KiwiHandler extends AbstractHandler
{
    public function parse(array $data_str, string $query): array
    {
        $data_str['regrinfo'] = $this->generic_parser_b($data_str['rawdata']);
        $data_str['regyinfo'] = $this->parseRegistryInfo($data_str['rawdata']);

        return $data_str;
    }
}
