<?php

declare (strict_types = 1);

namespace App\Rules;

use App\Rules\AuthRules;

class UserRules extends AuthRules
{
    public function __construct()
    {
        parent::__construct();
    }

    public function messages()
    {
        $this->createMessages('password', [
            'current' => "Podane hasło nie jest prawidłowe",
        ]);

        parent::messages();
    }
}