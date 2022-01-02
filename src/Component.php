<?php

declare (strict_types = 1);

namespace App;

use App\Exception\AppException;

class Component
{
    private static $default_path = "/../templates/component/";

    public static function render(string $path, array $params = []): void
    {
        $path = __DIR__ . self::$default_path . str_replace(".", "/", $path) . ".php";

        if (!file_exists($path)) {
            throw new AppException("Podana ścieżka do komponentu [ $path ] nie istnieje");
        }

        include $path;
    }
}
