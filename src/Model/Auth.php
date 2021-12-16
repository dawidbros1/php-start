<?php

declare (strict_types = 1);

namespace App\Model;

use App\Helper\Session;
use App\Validator\AbstractValidator;

class Auth extends AbstractValidator
{
    public $username;
    public $email;
    public $password;
    public $repeat_password;

    public function __construct(array $data)
    {
        if ($data != null) {
            $this->username = $data['username'] ?? null;
            $this->email = $data['email'] ?? null;
            $this->password = $data['password'] ?? null;
            $this->repeat_password = $data['repeat_password'] ?? null;
            $this->avatar = $data['avatar'] ?? null;
        }
    }

    public function isBusyEmail(?array $emails)
    {
        if ($emails != null) {
            if ($busy = in_array($this->email, $emails)) {
                Session::set("error:email:unique", "Podany adres email jest zajęty");
            }
        }

        return $busy ?? false;
    }

    public function hashPassword(string $method)
    {
        $this->password = hash($method, $this->password);
    }
}
