<?php

declare (strict_types = 1);

namespace App\Repository;

use PDO;

class SocialMediaRepository extends AbstractRepository
{
    public function getAll(): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM social_media");
        $stmt->execute();
        $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $media;
    }
}
