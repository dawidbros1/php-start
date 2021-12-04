<?php

declare (strict_types = 1);

namespace App\Repository;

use PDO;

class AuthRepository extends AbstractRepository
{
    public function createAccount(array $data): void
    {
        try {
            $data = [
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
                'created' => date('Y-m-d H:i:s'),
            ];

            $sql = "INSERT INTO users (username, email, password, created) VALUES (:username, :email, :password, :created)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się utworzyć nowego konta', 400, $e);
        }
    }

    public function login(string $email, string $password): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
        $stmt->execute([
            'email' => $email,
            'password' => $password,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
    }

    public function get(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
}