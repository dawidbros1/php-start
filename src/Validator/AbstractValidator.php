<?php

declare (strict_types = 1);

namespace App\Validator;

use App\Helper\Session;

class AbstractValidator
{
    protected static $rules;

    public static function initConfiguration($rules)
    {
        self::$rules = $rules;
    }

    // Metody walidacyjne wielokrotnego użytku
    protected function strlenBetween(string $variable, int $min, int $max)
    {
        if (strlen($variable) > $min && strlen($variable) < $max) {
            return true;
        }

        return false;
    }

    // Walidacja wielokrotnego użytku
    protected function validateUsername($username)
    {
        $min = self::$rules['username']['min'];
        $max = self::$rules['username']['max'];

        if ($this->strlenBetween($username, $min - 1, $max + 1) == false) {
            Session::set('error:username:strlen', "Nazwa użytkownika powinno zawierać od " . $min . " do " . $max . " znaków");
            return false;
        }

        return true;
    }
}