<?php

declare (strict_types = 1);

namespace App\Model;

use App\Helper\Session;
use App\Model\Model;
use App\Repository\AuthRepository;
use App\Repository\UserRepository;

class Auth extends Model
{
    public function __construct()
    {
        $this->repository = new AuthRepository();
    }

    public function register(array $data)
    {
        $user = new User($data);
        $user->escape();
        $this->repository->register($user);
        Session::set('success', 'Konto zostało utworzone');

    }

    public function login(array $data)
    {
        if ($id = $this->repository->login($data['email'], $data['password'])) {
            Session::set('user:id', $id);
            $lastPage = Session::getNextClear('lastPage');
        }

        return $id;
    }

    public function forgotPassword($email)
    {
        $location = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $code = rand(1, 1000000) . "_" . date('Y-m-d H:i:s');
        $hash = $this->hash($code, 'md5');

        Session::set($hash, $email);
        Session::set('created:' . $hash, time());

        // === //

        $data = [];
        $data['email'] = $email;
    }

    public function resetPasswrod($code)
    {
        $user = $this->userRepository->get(Session::get($code), 'email');
        $user->password = $this->hash($data['password']);
        $this->userRepository->update($user, 'password');
        Session::clearArray([$code, "created:" . $code]);
        Session::set('success', 'Hasło do konta zostało zmienione');
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
