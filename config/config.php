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
            'social_media' => 'uploads/images/social medium/',
        ],
    ],
    'default' => [
        'path' => [
            'avatar' => 'public/images/avatar.png',
        ],
    ],
    'hash' => [
        'method' => "sha256",
    ],
];
