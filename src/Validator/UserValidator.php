<?php

declare (strict_types = 1);

namespace App\Validator;

use App\Helper\Session;
use App\Validator\AbstractValidator;

abstract class UserValidator extends AbstractValidator
{
    protected function validatePassword($min = 5, $max = 17)
    {
        if ($this->password != $this->repeat_password) {
            Session::set('error:password:same', "Podane hasła nie są identyczne");
            $ok = false;
        }

        if ($this->strlenBetween($this->password, $min, $max) == false) {
            Session::set('error:password:strlen', "Hasło powinno zawierać od " . ($min + 1) . " do " . ($max - 1) . " znaków");
            $ok = false;
        }

        return $ok ?? true;
    }

    protected function compareCurrentPassword($password)
    {
        if ($password != $this->password) {
            Session::set('error:current_password:same', "Podane hasło jest nieprawidłowe");
            $ok = false;
        }

        return $ok ?? true;
    }
}