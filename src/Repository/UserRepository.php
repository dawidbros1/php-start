<?php

declare(strict_types=1);

namespace App\Repository;

use App\Base\BaseRepository;
use PDO;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        $this->table = "users";
        parent::__construct();
    }

    # Method returns all emails from "users" table
    public function getEmails(): array
    {
        $stmt = self::$pdo->prepare("SELECT email FROM $this->table");
        $stmt->execute();
        $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $emails;
    }
}
