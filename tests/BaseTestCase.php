<?php
/**
 * @copyright Copyright (c) 2020 Joshua Smith
 * @license   See LICENSE file
 */

namespace phpWhois;

use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected function skipWhenPhp8(): void
    {
        if (version_compare(PHP_VERSION_ID, '80000', '>=')) {
            self::markTestSkipped('Hangs under php-8.0');
        }
    }
}
