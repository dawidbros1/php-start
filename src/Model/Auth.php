<?php

declare(strict_types=1);

namespace App\Model;

use App\Repository\UserRepository;
use Phantom\Helper\Session;
use Phantom\Repository\DBFinder;

class Auth
{
    public static $hashMethod;
    protected $table = "users";

    public static function setHashMethod(string $hashMethod)
    {
        self::$hashMethod = $hashMethod;
    }

    public function register(User $user)
    {
        (new UserRepository())->create($user->hashPassword());
        Session::success('Konto zostało utworzone');
    }

    # Method sets session user:id if there is a user with matching data [e-mail, password]
    public function login(array $data)
    {
        $data['password'] = hash(self::$hashMethod, $data['password']);

        if ($user = DBFinder::getInstance($this->table)->find($data, User::class)) {
            Session::set('user:id', $user->getId());
        }

        return $user;
    }

    # Method checks if email is unique and return status
    public function isEmailUnique($email)
    {
        return (!in_array($email, (new UserRepository())->getEmails()));
    }

    # Method checks if email is unique and return status
    public function existsEmail($email)
    {
        return (in_array($email, (new UserRepository())->getEmails()));
    }
}