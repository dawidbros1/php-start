<?php

declare (strict_types = 1);

namespace App\Repository;

// use App\Exception\StorageException;
use PDO;

class SocialMediaRepository extends AbstractRepository
{
    public function save(array $data)
    {
        try {
            $data = [
                'id' => $data['id'] ?? null,
                'name' => $data['name'],
                'icon' => $data['icon'],
                'link' => $data['link'],
            ];

            $sql = "INSERT INTO social_media (id, name, icon, link) VALUES (:id, :name, :icon, :link)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się dodać medium społecznościowego', 400, $e);
        }
    }

    public function getAll(): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM social_media");
        $stmt->execute();
        $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $media;
    }
}
