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
        if ($status = ($this->validate($data)&!$this->isBusyEmail($data['email']))) {
            $data['avatar'] = self::$config->get('default.path.avatar');
            $data['password'] = $this->hash($data['password']);
            $data['role'] = "user";
            $data['created'] = date('Y-m-d H:i:s');

            $user = new User($data, true);

            if ($this->create($user, false)) {
                Session::success('Konto zostało utworzone');
            }
        }

        return $status;
    }
    public function isBusyEmail($email)
    {
        if (in_array($email, $this->repository->getEmails())) {
            Session::set("error:email:unique", "Podany adres email jest zajęty");
            return true;
        }
        return false;
    }
}
