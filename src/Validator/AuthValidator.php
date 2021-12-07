<?php

declare (strict_types = 1);

namespace App\Validator;

use App\Helper\Session;
use App\Validator\AbstractValidator;

abstract class AuthValidator extends AbstractValidator
{
    protected function validateEmail(string $email)
    {
        $rule = $this->rules['email'];

        // Sprawdza czy email zawiera niedozwolone znaki [ ą, ę, ć ... ]
        if ($email != filter_var($email, FILTER_SANITIZE_EMAIL)) {
            Session::set('error:email:sanitize', $rule['sanitize.message']);
            $ok = false;
        }

        // Sprawdza czy email jest poprawny
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::set('error:email:validate', $rule['validate.message']);
            $ok = false;
        }

        return $ok ?? true;
    }
}