<?php
/**
 * @copyright Copyright (c) 2020 Joshua Smith
 * @license   See LICENSE file
 */

namespace phpWhois\Cli;

class CliHelper
{
    /**
     * @param string $filename
     *
     * @return string[]
     */
    public function loadDomainList(string $filename): array
    {
        $lines   = file($filename);
        $domains = [];

        foreach ($lines as $line) {
            $pos = strpos($line, '/');

            if ($pos !== false) {
                $line = substr($line, 0, $pos);
            }

            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $parts = explode(' ', str_replace("\t", ' ', $line));
            $key   = $parts[0];

            for ($i = 1, $count = count($parts); $i < $count; $i++) {
                if ($parts[$i] !== '') {
                    if ($key) {
                        $domains[$key] = $parts[$i];
                        $key           = false;
                    } else {
                        $domains[] = $parts[$i];
                    }
                }
            }
        }

        return $domains;
    }
}