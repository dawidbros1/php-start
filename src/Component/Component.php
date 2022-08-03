<?php

declare (strict_types = 1);

namespace App\Component;

use App\Component\Rules;
use App\Exception\AppException;

class Component
{
    private static $default_path = "/../../templates/component/";
    private static $input = [];

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

        Component::requireParams($component, $params);
        include $path;
    }

    public static function requireParams(string $path, array $params)
    {
        $rules = Rules::get($path);

        foreach ($rules ?? [] as $rule) {
            if (!array_key_exists($rule, $params)) {
                throw new AppException("Wymagany parametr [ $rule ] nie został wprowadzony");
            }
        }
    }
}
