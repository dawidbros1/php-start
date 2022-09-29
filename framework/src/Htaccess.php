<?php

declare (strict_types = 1);

namespace Phantom;

class Htaccess
{
    private $text;
    private $file = ".htaccess";

    public function __construct()
    {
        $this->text = file_get_contents($this->file);
    }

    public function write($line)
    {
        if (strpos($this->text, $line) === false) {
            file_put_contents($this->file, $line, FILE_APPEND);
        }
    }
}
