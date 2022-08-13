<?php

declare (strict_types = 1);

namespace App\Repository;

use PDO;

class AuthRepository extends Repository
{
    public function __construct()
    {
        $this->table = "users";
        parent::__construct();
    }

    public function getEmails(): array
    {
        $stmt = self::$pdo->prepare("SELECT email FROM users");
        $stmt->execute();
        $emails = $stmt->fetchAll(PDO::FETCH_COLUMN, 'email');
        return $emails;
    }
}
