<?php

declare (strict_types = 1);

namespace App\Validator;

use App\Helper\Session;
use App\Validator\AbstractValidator;

abstract class AuthValidator extends AbstractValidator
{
    protected function validateEmail()
    {
        // Sprawdza czy email zawiera niedozwolone znaki [ ą, ę, ć ... ]
        if ($this->email != filter_var($this->email, FILTER_SANITIZE_EMAIL)) {
            Session::set('error:email:sanitize', 'Adres email zawiera niedozwolone znaki');
            $ok = false;
        }

        // Sprawdza czy email jest poprawny
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            Session::set('error:email:validate', 'Adres email nie jest prawidłowy');
            $ok = false;
        }

        return $ok ?? true;
    }

    protected function validatePassword()
    {
        $min = self::$rules['password']['min'];
        $max = self::$rules['password']['max'];

        if ($this->password != $this->repeat_password) {
            Session::set('error:password:same', "Podane hasła nie są identyczne");
            $ok = false;
        }

        if ($this->strlenBetween($this->password, $min - 1, $max + 1) == false) {
            Session::set('error:password:strlen', "Hasło powinno zawierać od " .$min . " do " . $max . " znaków");
            $ok = false;
        }

        return $ok ?? true;
    }
}