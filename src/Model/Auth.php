<?php

declare (strict_types = 1);

namespace App\Model;

use App\Repository\AuthRepository;
use App\Validator\AuthValidator;

class Auth extends AuthValidator
{
    public $username;
    public $email;
    public $password;
    public $repeat_password;

    public function __construct()
    {
        $this->repository = new AuthRepository();
    }

    public function register(array $data)
    {
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->repeat_password = $data['repeat_password'];

        if (!$this->validateUsername()) {$ok = false;}
        if (!$this->validateEmail()) {$ok = false;}
        if (!$this->validatePassword()) {$ok = false;}

        if ($ok ?? true) {
            $this->repository->createAccount($data);
        }

        return $ok ?? true;
    }

    public function login(string $email, string $password)
    {
        $user = $this->repository->login($email, $password);
        return $user ?? null;
    }
}