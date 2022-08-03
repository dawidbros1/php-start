<?php

declare (strict_types = 1);

namespace App\Component;

use App\Exception\AppException;

abstract class Rules
{
    private static $rules = [
        'button' => [
            'back' => ['action'],
            'dropdown' => ['target', "text"],
        ],

        'form' => [
            'checkbox' => ['name', 'label'],
            'input' => ['type', 'name'],
            'select' => ['name', 'options', 'selected'],
            'submit' => [],
        ],

        'item' => [
            'page' => ['item', 'route'],
            'category' => ['item', 'route'],
            'form' => [
                'open' => [],
                'close' => [],
                'delete' => ['action', 'id', 'target'],
            ],
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
