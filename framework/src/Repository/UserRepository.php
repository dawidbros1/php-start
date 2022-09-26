<?php

declare (strict_types = 1);

namespace Phantom\Repository;

use Phantom\Repository\Repository;

class UserRepository extends Repository
{
    public function __construct()
    {
        $this->table = "users";
        parent::__construct();
    }
}
