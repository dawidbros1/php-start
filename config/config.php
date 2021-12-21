<?php

declare (strict_types = 1);

use App\Model\Config;

return new Config(
    [
        'db' => [
            'host' => 'localhost',
            'database' => 'project',
            'user' => 'root',
            'password' => '',
        ],
        'mail' => [
            'to' => 'wsparcie@mojeprojekty.tk',
        ],
        'upload' => [
            'path' => [
                'avatar' => 'uploads/images/avatar/',
            ],
        ],
        'default' => [
            'path' => [
                'avatar' => 'public/images/avatar.png',
                'medium' => 'public/images/SocialMedia/',
            ],
        ],
        'hash' => [
            'method' => 'sha256',
        ],
    ]
);
