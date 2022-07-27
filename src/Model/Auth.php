<?php

declare (strict_types = 1);

namespace App\Model;

use App\Helper\Session;
use App\Model\Model;
use App\Repository\AuthRepository;
use App\Rules\AuthRules;

class Auth extends Model
{
    public $fillable = ['id', 'username', 'email', 'password', 'avatar', 'role', 'created'];

    public function __construct()
    {
        $this->repository = new AuthRepository();
        $this->rules = new AuthRules();
    }

    public function register(array $data)
    {
        if ($status = ($this->validate($data) & !$this->isBusyEmail($data['email']))) {
            $data['password'] = $this->hash($data['password']);
            $data['role'] = "user";
            $data['created'] = date('Y-m-d H:i:s');

            if ($this->create($data, false)) {
                Session::success('Konto zostało utworzone');
            }
        }

        return $status;
    }

    public function login(array $data)
    {
        $data['password'] = $this->hash($data['password']);

        if ($user = $this->find(['email' => $data['email'], 'password' => $data['password']])) {
            Session::set('user:id', $user->id);
        }

        return $user;
    }

    public function resetPassword($data, $code)
    {
        if ($status = $this->validate($data)) {
            $user = $this->find(['email' => Session::get($code)]);
            $user->update(['password' => $this->hash($data['password'])], ['password'], false);
            Session::clearArray([$code, "created:" . $code]);
            Session::success('Hasło do konta zostało zmienione');
        }
        return $user ?? null;
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
