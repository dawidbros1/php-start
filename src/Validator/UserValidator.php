<?php

declare (strict_types = 1);

namespace App\Validator;

use App\Helper\Session;
use App\Validator\AbstractValidator;

abstract class UserValidator extends AbstractValidator
{
    protected function validatePasswords($data)
    {
        $rule = $this->rules['password'];
        $password = $data['password'];
        $repeat_password = $data['repeat_password'];
        $current_password = $data['current_password'];

        $ok = parent::validatePassword($password, $repeat_password);

        if ($current_password != $this->password) {
            Session::set('error:current_password:same', $rule['current.message']);
            $ok = false;
        }

        return $ok;
    }
}