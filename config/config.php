<?php

declare (strict_types = 1);

return [
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
];
