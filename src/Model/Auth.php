<?php

declare (strict_types = 1);

namespace App\Model;

use App\Helper\Session;
use App\Repository\AuthRepository;
use App\Rules\AuthRules;
use App\Validator\AbstractValidator;

class Auth extends AbstractValidator
{
    public $rules;

    public function __construct()
    {
        $this->repository = new AuthRepository();
        $this->rules = (new AuthRules)->get();
    }

    public function register(array $data)
    {
        $emails = $this->repository->getEmails();

        if (!$unique = !in_array($data['email'], $emails)) {
            Session::set("error:email:unique", "Podany adres email jest zajęty");
        }

        if ($ok = $this->validate($data) && $unique) {
            $this->repository->createAccount($data);
        }

        return $ok;
    }

    public function login(string $email, string $password)
    {
        $user = $this->repository->login($email, $password);
        return $user ?? null;
    }
}
