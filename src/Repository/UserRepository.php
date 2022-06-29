<?php

declare (strict_types = 1);

namespace App\Repository;

use App\Model\User;
use App\Repository\Repository;
use PDO;

class UserRepository extends Repository
{
    public function get($value, $column): ?array
    {
        $user = null;
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE $column=:$column");
        $stmt->execute([$column => $value]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function update(User $user, string $property): void
    {
        $user->escape();
        $data = $user->getArray(['id', $property]);
        $sql = "UPDATE users SET $property=:$property WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($data);
    }
}
