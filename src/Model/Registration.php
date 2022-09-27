<?php

declare (strict_types = 1);

namespace App\Model;

use App\Model\User;
use Phantom\Helper\Session;
use Phantom\Model\Model;

class Registration extends Model
{
    public function register(array $data)
    {
        $user = new User($data);

        if ($status = ($user->validate($data)&!$this->isUnique($user))) {
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
    public function isUnique($user)
    {
        if (in_array($user->get('email'), $user->repository->getEmails())) {
            Session::set("error:email:unique", "Podany adres email jest zajęty");
            return true;
        }
        return false;
    }
}
