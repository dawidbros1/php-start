<?php

declare (strict_types = 1);

namespace App\Model;

use App\Helper\Session;

class User extends Model
{
    public $id;
    public $username;
    public $email;
    public $created;
    public $password;
    public $avatar;
    public $role;

    private static $config;

    public function __construct($data)
    {
        $this->id = $data['id'] ?? null;
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->avatar = $data['avatar'];
        $this->role = $data['role'] ?? null;
        $this->created = $data['created'] ?? null;
    }

    public function logout()
    {
        Session::clear('user:id');
    }

    public function isAdmin()
    {
        return (bool) ($this->role === "admin");
    }

    // ===== ===== ===== ===== =====

    public function deleteAvatar()
    {
        if (file_exists($this->avatar)) {
            unlink($this->avatar);
        }
    }
}
