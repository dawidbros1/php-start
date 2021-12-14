<?php

declare (strict_types = 1);

namespace App\Model;

use App\Helper\Session;
use App\Repository\UserRepository;
use App\Rules\UserRules;
use App\Validator\UserValidator;

class User extends UserValidator
{
    public $id;
    public $username;
    public $email;
    public $created;
    public $password;
    public $avatar;
    public $role;
    public $rules;

    private static $config;

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
            $this->avatar = $data['avatar'];
            $this->role = $data['role'];
            $this->created = $data['created'];
        }
    }

    public function updateUsername($username)
    {
        $this->rules = new UserRules();

        if ($ok = $this->validate(['username' => $username])) {
            $this->repository->updateUsername($username);
        }

        return $ok;
    }

    public function updatePassword($data, $method)
    {
        $this->rules = new UserRules();

        if (!$same = ($this->password == hash('sha256', $data['current_password']))) {
            Session::set("error:password:current", "Podane hasło jest nieprawidłowe");
        }

        if ($ok = $this->validate($data) && $same) {
            $this->repository->updatePassword(hash($method, $data['password']));
        }

        return $ok;
    }

    public function updateAvatar($FILE, $path)
    {
        if ($this->validateAvatarFile($FILE)) {
            $target_dir = $path;
            $type = strtolower(pathinfo($FILE['name'], PATHINFO_EXTENSION));
            $FILE['name'] = $this->hashAvatarName($FILE['name']);
            $target_file = $target_dir . basename($FILE["name"]);

            if ($ok = move_uploaded_file($FILE["tmp_name"], $target_file)) {
                $this->deleteOldAvatar();
                $this->repository->updateAvatar($target_dir . $FILE['name']);
            } else {
                // Np: Gdy ścieżka jest niepoprawna [ nie istnieje ]
                Session::set('error', 'Przepraszamy, wystąpił problem w trakcie wysyłania pliku');
            }
        }

        return $ok ?? false;
    }

    public function logout()
    {
        Session::clear('user:id');
    }

    public function isAdmin()
    {
        return (bool) ($this->role === "admin");
    }

    private function hashAvatarName(string $name)
    {
        $type = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $name = hash('md5', date('Y-m-d H:i:s') . "_" . $name);
        $fileName = $name . '.' . $type;
        return $fileName;
    }

    private function deleteOldAvatar()
    {
        $oldAvatar = $this->avatar;

        if ($oldAvatar != "" || $oldAvatar != null) {
            if (file_exists($oldAvatar)) {
                unlink($oldAvatar);
            }
        }
    }
}
