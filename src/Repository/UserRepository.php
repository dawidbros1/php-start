<?php

declare (strict_types = 1);

namespace App\Repository;

use PDO;
use Phantom\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
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

    public function create($user)
    {
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $avatar = null;
        $role = 'user';
        $created_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO users (username, email, password, avatar, role, created_at) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$username, $email, $password, $avatar, $role, $created_at]);
    }

    public function updateAvatar($user, $name)
    {
        $sql = "UPDATE users SET avatar = :avatar WHERE id = :id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->bindParam('avatar', $name, PDO::PARAM_STR);
        $stmt->bindParam('id', $user->getId(), PDO::PARAM_INT);
        $stmt->execute();
    }
}
