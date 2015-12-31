<?php

namespace phpWhois\Parser;

class ParserA extends ParserAbstract {

    protected function parseLines(array $lines)
    {
        $comment = [];
        $rows = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            if (preg_match('/^%/', $line)) {
                $comment[] = $line;
                continue;
            }

            $row = preg_split('/(:)/', $line, 2);

            $row[1] = trim($row[1]);

            $rows[$row[0]] = $row[1];
        }

        return [
            'comment' => $comment,
            'rows' => $rows,
        ];
    }
}