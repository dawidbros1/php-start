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
    }

    public function register(array $data)
    {
        $this->rules = (new AuthRules)->get();
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

        if ($user == null) {
            $emails = $this->repository->getEmails();

            if (in_array($email, $emails)) {
                Session::set("error:password:incorrect", "Wprowadzone hasło jest nieprawidłowe");
            } else {
                Session::set("error:email:null", "Podany adres email nie istnieje");
            }
        }

        return $user;
    }

    private function checkEmail()
    {
        $emails = $this->repository->getEmails();
    }
}
