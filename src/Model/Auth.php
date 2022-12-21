<?php

declare (strict_types = 1);

namespace App\Model;

use App\Repository\UserRepository;
use Phantom\Helper\Session;
use Phantom\Model\AbstractModel;

class Auth extends AbstractModel
{
    private $id, $username, $email, $password;
    protected $table = "users";
    public function create()
    {
        $this->password = hash('sha256', $this->password);
        (new UserRepository())->create($this);
        Session::success('Konto zostało utworzone');
    }

    # Method sets session user:id if there is a user with matching data [e-mail, password]
    public function login(array $data)
    {
        $data['password'] = hash('sha256', $data['password']);

        if ($user = $this->find(['email' => $data['email'], 'password' => $data['password']], User::class)) {
            Session::set('user:id', $user->getId());
        }

        return $user;
    }

    # Method checks if email exists and return status
    public function existsEmail($email)
    {
        return in_array($email, $this->repository->getEmails());
    }

    # Method checks if email is unique and return status
    public function isEmailUnique($user)
    {
        if (in_array($user->get('email'), $user->repository->getEmails())) {
            Session::set("error:email:unique", "Podany adres email jest zajęty");
            return false;
        }

        return true;
    }

    # GETTERS * SETTERS
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
