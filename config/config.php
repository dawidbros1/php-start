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
            'avatar' => 'public/images/uploads/avatar/',
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
