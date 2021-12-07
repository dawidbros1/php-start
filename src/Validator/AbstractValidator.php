<?php

declare (strict_types = 1);

namespace App\Validator;

use App\Helper\Session;

class AbstractValidator
{
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
        $rule = $this->rules['username'];
        $min = $rule['min.value'];
        $max = $rule['max.value'];

        if ($this->strlenBetween($username, $min - 1, $max + 1) == false) {
            Session::set('error:username:strlen', $rule['strlen.message']);
            return false;
        }

        return true;
    }

    protected function validatePassword(string $password, string $repeat_password)
    {

        $rule = $this->rules['password'];
        $min = $rule['min.value'];
        $max = $rule['max.value'];

        if ($password != $repeat_password) {
            Session::set('error:password:same', $rule['same.message']);
            $ok = false;
        }

        if ($this->strlenBetween($password, $min - 1, $max + 1) == false) {
            Session::set('error:password:strlen', $rule['strlen.message']);
            $ok = false;
        }

        return $ok ?? true;
    }
}