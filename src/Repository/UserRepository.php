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

        if (!$user) {$user = null;}
        return $user;
    }

    public function updateUsername($username)
    {
        $data = ['username' => $username];
        $sql = "UPDATE users SET username=:username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    public function updatePassword($password)
    {
        $data = ['password' => $password];
        $sql = "UPDATE users SET password=:password";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }
}