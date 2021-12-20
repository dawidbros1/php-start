<?php

declare (strict_types = 1);

namespace App\Repository;

use App\Model\User;
use PDO;

class AuthRepository extends Repository
{
    public function register(User $user): void
    {
        try {
            $data = [
                'username' => $user->username,
                'email' => $user->email,
                'password' => $user->password,
                'avatar' => $user->avatar,
                'role' => "user",
                'created' => date('Y-m-d H:i:s'),
            ];

            $sql = "INSERT INTO users (username, email, password, avatar, role, created) VALUES (:username, :email, :password, :avatar, :role, :created)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się utworzyć nowego konta', 400, $e);
        }
    }

    public function login(string $email, string $password)
    {
        $id = null;
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email=:email AND password=:password");
        $stmt->execute([
            'email' => $email,
            'password' => $password,
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {$id = $data['id'];}
        return $id;
    }

    public function getEmails()
    {
        $stmt = $this->pdo->prepare("SELECT email FROM users");
        $stmt->execute();
        $emails = $stmt->fetchAll(PDO::FETCH_COLUMN, 'email');
        return $emails;
    }
}
