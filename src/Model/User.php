<?php

declare (strict_types = 1);

namespace App\Model;

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

    // public function updateAvatar($FILE, $path)
    // {
    //     $this->rules = new UserRules();
    //     $this->rules->selectType('avatar');

    //     if ($this->validateImage($FILE, $this->rules)) {
    //         $target_dir = $path;
    //         $type = strtolower(pathinfo($FILE['name'], PATHINFO_EXTENSION));
    //         $FILE['name'] = $this->hashAvatarName($FILE['name']);
    //         $target_file = $target_dir . basename($FILE["name"]);

    //         if ($ok = move_uploaded_file($FILE["tmp_name"], $target_file)) {
    //             $this->deleteFile($this->avatar);
    //             $this->repository->updateAvatar($target_dir . $FILE['name']);
    //         } else {
    //             // Np: Gdy ścieżka jest niepoprawna [ nie istnieje ]
    //             Session::set('error', 'Przepraszamy, wystąpił problem w trakcie wysyłania pliku');
    //         }
    //     }

    //     return $ok ?? false;
    // }

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
