<?php

declare (strict_types = 1);

namespace App\Repository;

use App\Repository\Repository;

class UserRepository extends Repository
{
    public function __construct()
    {
        $this->table = "users";
        parent::__construct();
    }
}
