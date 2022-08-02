<?php

declare (strict_types = 1);

namespace App;

use App\Exception\AppException;

class Component
{
    private static $default_path = "/../templates/component/";

    public static function render(string $component, array $params = []): void
    {

        $path = __DIR__ . self::$default_path . str_replace(".", "/", $component) . ".php";

        if (!file_exists($path)) {
            throw new AppException("Komponent [ $component ] nie istnieje");
        }

        if (array_key_exists('description', $params)) {
            $params['label'] = $params['description'];
            $params['placeholder'] = $params['description'];
            unset($params['description']);
        }

        include $path;
    }
}
