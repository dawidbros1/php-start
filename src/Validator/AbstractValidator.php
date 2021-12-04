<?php

declare (strict_types = 1);

namespace App\Validator;

use App\Helper\Session;

class AbstractValidator
{
    // Metody walidacyjne wielokrotnego użytku
    protected function strlenBetween($variable, $min, $max)
    {
        if (strlen($variable) > $min && strlen($variable) < $max) {
            return true;
        }

        return false;
    }

    // Walidacja wielokrotnego użytku
    protected function validateUsername($min = 5, $max = 17)
    {
        if ($this->strlenBetween($this->username, $min, $max) == false) {
            Session::set('error:username:strlen', "Nazwa użytkownika powinno zawierać od " . ($min + 1) . " do " . ($max - 1) . " znaków");
            return false;
        }

        return true;
    }
}