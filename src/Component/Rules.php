<?php

declare (strict_types = 1);

namespace App\Component;

use App\Exception\AppException;

abstract class Rules
{
    private static $rules = [
        'button' => [
            'link' => ['action', 'text'],
            'dropdown' => ['target', "text"],
        ],

        'form' => [
            'checkbox' => ['id', 'name', 'label', 'checked'],
            'input' => ['type', 'name'],
            'select' => ['name', 'options', 'selected', 'show', 'label'],
            'submit' => ['text'],
        ],

        'error' => ['names', 'type'],
    ];

    public static function get($path)
    {
        $output = self::$rules;
        $array = explode(".", $path);

        foreach ($array as $name) {
            if (array_key_exists($name, $output)) {
                $output = $output[$name];
            } else {
                throw new AppException("Reguły podanego komponentu [ $path ] nie zostały zarejestrowane");
            }
        }

        return $output;
    }
}
