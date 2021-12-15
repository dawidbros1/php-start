<?php

declare (strict_types = 1);

namespace App;

class Error
{
    public static function render(string $page, ?string $text): void
    {
        if ($text != null) {
            $text = htmlentities($text);
            include __DIR__ . "/../templates/error/" . $page . ".php";
        }
    }
}
