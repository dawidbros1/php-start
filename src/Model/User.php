<?php

declare (strict_types = 1);

namespace App\Model;

use App\Helper\Session;
use App\Repository\UserRepository;
use App\Rules\UserRules;

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

    public function __construct()
    {
        $this->rules = new UserRules();
        $this->repository = new UserRepository();
    }

    public function logout()
    {
        Session::clear('user:id');
        Session::set('success', "Nastąpiło wylogowanie z systemu");
    }

    public function updateUsername($data)
    {
        if ($this->validate($data)) {
            $this->update($data);
            $this->repository->update($this, 'username');
            Session::set('success', "Nazwa użytkownika została zmieniona");
        }
    }

    public function updatePassword($data)
    {
        if (!$same = ($this->password == $this->hash($data['current_password']))) {
            Session::set("error:current_password:same", "Podane hasło jest nieprawidłowe");
        }

        if ($this->validate($data) && $same) {
            $data['password'] = $this->hash($data['password']);
            $this->update($data);
            $this->repository->update($this, 'password');
            Session::set('success', 'Hasło zostało zaktualizowane');
        }
    }

    public function updateAvatar($file, $path, $defaultAvatar)
    {
        if ($this->validateImage($file, 'avatar')) {
            $file = $this->hashFile($file);

            if ($this->uploadFile($path, $file)) {
                if ($this->avatar != $defaultAvatar) {
                    $this->deleteAvatar();
                }

                $this->update(['avatar' => $path . $file['name']]);
                $this->repository->update($this, 'avatar');
                Session::set('success', 'Awatar został zaktualizowany');
            }
        }
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
