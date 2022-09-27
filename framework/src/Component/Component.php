<?php

declare (strict_types = 1);

namespace Phantom\Component;

use Phantom\Exception\AppException;

class Component
{
    private static $default_path = "/../../../templates/";
    public static function render(string $component, array $params = []): void
    {
        $namespace = "App\Component\\" . str_replace(".", "\\", $component);

        if (!class_exists($namespace)) {
            throw new AppException("Klasa [ $component ] nie istnieje");
        }

        $component = new $namespace;

        $path = __DIR__ . self::$default_path . $component->template;

        if (!file_exists($path)) {
            throw new AppException("Plik [ $path ] nie istnieje");
        }

        self::requireParams($component->require, $params);

        $styles = Component::getStyles($params);

        foreach ($params as $key => $param) {
            if (!in_array($key, ['class', 'mt', 'col'])) {
                ${$key} = $param;
            }
        }

        include $path;
    }

    private static function getStyles(array $params): string
    {
        $mt = $params['mt'] ?? "mt-3";
        $class = $params['class'] ?? "";
        $col = $params['col'] ?? "col-12";

        return "$mt $class $col";
    }

    public static function requireParams(array $require, array $params)
    {
        foreach ($require ?? [] as $param) {
            if (!array_key_exists($param, $params)) {
                throw new AppException("Wymagany parametr [ $param ] nie został wprowadzony");
            }
        }
    }
}
