<?php
/**
 * @copyright Copyright (c) 2020 Joshua Smith
 * @license   See LICENSE file
 */

namespace phpWhois\Handlers;

/**
 * HandlerInterface
 */
interface HandlerInterface
{
    /**
     * @param array $data_str
     * @param string $query
     *
     * @return array
     */
    public function parse(array $data_str, string $query): array;
}
