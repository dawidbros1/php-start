<?php

declare (strict_types = 1);

namespace App\Repository;

use App\Model\User;
use App\Repository\Repository;
use PDO;

class UserRepository extends Repository
{
    public function get(int $id): ?User
    {
        $user = null;
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {$user = new User($data);}
        return $user;
    }

    public function updateUsername($username)
    {
        $data = ['username' => $username, 'id' => self::$user_id];
        $sql = "UPDATE users SET username=:username WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    public function updatePassword($password)
    {
        $data = ['password' => $password, 'id' => self::$user_id];
        $sql = "UPDATE users SET password=:password WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    public function updateAvatar($avatar)
    {
        $data = ['avatar' => $avatar, 'id' => self::$user_id];
        $sql = "UPDATE users SET avatar=:avatar WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }
}
