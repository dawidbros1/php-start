<?php

declare (strict_types = 1);

namespace App\Model;

use App\Helper\Session;
use App\Repository\UserRepository;

class User
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $created;

    public function __construct()
    {
        $this->repository = new UserRepository();

        if ($user_id = Session::get('user:id')) {
            $data = $this->repository->get((int) $user_id);

            $this->id = $data['id'];
            $this->username = $data['username'];
            $this->email = $data['email'];
            $this->password = $data['password'];
            $this->created = $data['created'];
        }
    }
}