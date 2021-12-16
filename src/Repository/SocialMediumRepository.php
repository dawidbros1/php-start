<?php

declare (strict_types = 1);

namespace App\Repository;

use App\Exception\StorageException;
use App\Model\SocialMedium;
use PDO;

class SocialMediumRepository extends AbstractRepository
{
    public function save(SocialMedium $medium)
    {
        try {
            $data = [
                'id' => $medium->id ?? null,
                'name' => $medium->name,
                'icon' => $medium->icon,
                'link' => $medium->link,
            ];

            if ($medium->id !== null) {
                $sql = "UPDATE social_media SET name=:name, icon=:icon, link=:link WHERE id=:id";
            } else {
                $sql = "INSERT INTO social_media (id, name, icon, link) VALUES (:id, :name, :icon, :link)";
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się wykonać pożądanej akcji', 400, $e);
        }
    }

    // ===== ===== ===== ===== =====

    public function getAll(): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM social_media");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->createObjects($data);
    }

    public function get(int $id): ?SocialMedium
    {
        try {
            $sql = "SELECT * FROM social_media WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się pobrać informacji z bazy danych', 400, $e);
        }

        if (count($data) == 0) {return null;}
        return $this->createObjects($data);
    }

    // ===== ===== ===== ===== =====

    private function createObjects($data)
    {
        $output = [];

        foreach ($data as $array) {
            $output[] = new SocialMedium($array);
        }

        if (count($output) == 1) {return $output[0];}

        return $output;
    }
}
