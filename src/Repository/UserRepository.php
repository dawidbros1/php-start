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
        return $user;
    }
}