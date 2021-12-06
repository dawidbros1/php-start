<?php

declare (strict_types = 1);

namespace App\Model;

use App\Helper\Session;
use App\Repository\UserRepository;
use App\Validator\UserValidator;

class User extends UserValidator
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $created;

    public function __construct()
    {
        $this->repository = new UserRepository();
        return $this->fill();
    }

    public function fill()
    {
        $user_id = Session::get('user:id');
        if ($data = $this->repository->get((int) $user_id)) {
            $this->id = $data['id'];
            $this->username = $data['username'];
            $this->email = $data['email'];
            $this->password = $data['password'];
            $this->created = $data['created'];
        }
    }

    public function updateUsername($username)
    {
        if (!$this->validateUsername($username)) {$ok = false;}

        if ($ok ?? true) {
            $this->repository->updateUsername($username);
        }

        return $ok ?? true;
    }

    public function updatePassword($data)
    {
        if (!$this->compareCurrentPassword($data['current_password'])) {$ok = false;}
        // if (!$this->validatePassword($username)) {$ok = false;}

        if ($ok ?? true) {
            // $this->repository->updatePassword($data['password']);
        }

        return $ok ?? true;
    }

    public function logout()
    {
        Session::clear('user:id');
    }
}