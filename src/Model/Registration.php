<?php

declare (strict_types = 1);

namespace App\Model;

use App\Model\User;
use Phantom\Helper\Session;
use Phantom\Model\Model;

class Registration extends Model
{
    # Method adds new user
    public function register(array $data)
    {
        $user = new User($data);

        if ($status = ($user->validate($data)&($this->isEmailUnique($user)))) {
            unset($data);
            $data['avatar'] = self::$config->get('default.path.avatar');
            $data['role'] = "user";
            $data['created'] = date('Y-m-d H:i:s');

            $user->setArray($data);
            $user->hashPassword();

            if ($user->create(false)) {
                Session::success('Konto zostało utworzone');
            }
        }

        return $status;
    }

    # Method checks if email is unique and return status
    public function isEmailUnique($user)
    {
        if ($user->existsEmail($user->get('email'))) {
            Session::set("error:email:unique", "Podany adres email jest zajęty");
            return false;
        }

        return true;
    }
}
