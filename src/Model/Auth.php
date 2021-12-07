<?php

declare (strict_types = 1);

namespace App\Model;

use App\Repository\AuthRepository;
use App\Rules\AuthRules;
use App\Validator\AuthValidator;

class Auth extends AuthValidator
{
    public $rules;

    public function __construct()
    {
        $this->repository = new AuthRepository();
        $this->rules = (new AuthRules)->get();
    }

    public function register(array $data)
    {

        if (!$this->validateUsername($data['username'])) {$ok = false;}
        if (!$this->validateEmail($data['email'])) {$ok = false;}
        if (!$this->validatePassword($data['password'], $data['repeat_password'])) {$ok = false;}

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