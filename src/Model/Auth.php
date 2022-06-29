<?php

declare (strict_types = 1);

namespace App\Model;

use App\Helper\Session;
use App\Model\Model;
use App\Repository\AuthRepository;
use App\Repository\UserRepository;
use App\Rules\AuthRules;

class Auth extends Model
{
    public function __construct()
    {
        $this->repository = new AuthRepository();
        $this->rules = new AuthRules();
    }

    public function register(array $data)
    {
        if ($status = ($this->validate($data) & !$this->isBusyEmail($data['email']))) {
            $data['password'] = $this->hash($data['password']);
            $user = new User();
            $user->update($data);
            $user->escape();
            $this->repository->register($user);
            Session::set('success', 'Konto zostało utworzone');
        }

        return $status;
    }

    public function login(array $data)
    {
        $data['password'] = $this->hash($data['password']);

        if ($id = $this->repository->login($data['email'], $data['password'])) {
            Session::set('user:id', $id);
            $lastPage = Session::getNextClear('lastPage');
        }

        return $id;
    }

    public function resetPassword($data, $code)
    {
        if ($status = $this->validate($data)) {
            $user = $this->userRepository->get(Session::get($code), 'email');
            $user->password = $this->hash($data['password']);
            $this->userRepository->update($user, 'password');
            Session::clearArray([$code, "created:" . $code]);
            Session::set('success', 'Hasło do konta zostało zmienione');
        }
        return $status;
    }

    public function isBusyEmail($email)
    {
        if ($this->existsEmail($email)) {
            Session::set("error:email:unique", "Podany adres email jest zajęty");
            return true;
        }
        return false;
    }

    public function existsEmail($email)
    {
        return in_array($email, $this->repository->getEmails());
    }
}
