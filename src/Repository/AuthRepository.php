<?php

declare (strict_types = 1);

namespace App\Repository;

use App\Model\Auth;
use PDO;

class AuthRepository extends AbstractRepository
{
    public function createAccount(Auth $model): void
    {
        try {
            $data = [
                'username' => $model->username,
                'email' => $model->email,
                'password' => $model->password,
                'avatar' => $model->avatar,
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

    public function login(Auth $model): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
        $stmt->execute([
            'email' => $model->email,
            'password' => $model->password,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {return null;} else {return $user;}
    }

    public function get(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function getEmails()
    {
        $stmt = $this->pdo->prepare("SELECT email FROM users");
        $stmt->execute();
        $emails = $stmt->fetchAll(PDO::FETCH_COLUMN, 'email');
        return $emails;
    }
}
