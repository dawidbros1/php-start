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

    public function update(User $user, string $property)
    {
        $data = $user->getArray(['id', $property]);
        $string = "$property=:$property";

        $sql = "UPDATE users SET " . $string . " WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }
}
