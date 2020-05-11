<?php

// Match Travis CI configuration
error_reporting(E_ALL);

// Disable deprecation warnings until updating dependency
//        "algo26-matthias/idna-convert": "^3.0"
error_reporting(E_ALL & ~E_DEPRECATED);

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require(__DIR__ . '/../vendor/autoload.php');
}
