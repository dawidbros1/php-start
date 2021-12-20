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

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {$user = new User($data);}
        return $user;
    }

    public function update(User $user, string $property)
    {
        $sql = "UPDATE users SET $property=:$property WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(":id", $user->id, PDO::PARAM_INT);
        $stmt->bindParam(":" . $property, $user->$property, PDO::PARAM_STR);
        $stmt->execute();
    }
}
