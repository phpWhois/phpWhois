<?php

namespace phpWhois\Parser;

interface iParser {
    public function setData($data);
    public function parseData();
}