<?php

declare (strict_types = 1);

namespace App\Repository;

use App\Model\User;
use App\Repository\Repository;
use PDO;

class UserRepository extends Repository
{
    public function get($param, $type = "id"): ?User
    {
        $user = null;
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE $type=:$type");
        $stmt->execute([$type => $param]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {$user = new User($data);}
        return $user;
    }

    public function update(User $user, string $property)
    {
        $user->escape();
        $data = $user->getArray(['id', $property]);
        $sql = "UPDATE users SET $property=:$property WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($data);
    }
}
