<?php

declare (strict_types = 1);

namespace App\Repository;

use App\Repository\AbstractRepository;
use PDO;

class UserRepository extends AbstractRepository
{
    public function get(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            self::$user_id = (int) $user['id'];
        } else {
            $user = null;
        }

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
