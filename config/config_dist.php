<?php

declare (strict_types = 1);

return [
    'db' => [
        'host' => 'localhost',
        'database' => '',
        'user' => '',
        'password' => '',
    ],
    'mail' => [
        'to' => 'email',
    ],
    'upload' => [
        'path' => [
            'avatar' => 'uploads/images/avatar/',
        ],
    ],
    'default' => [
        'path' => [
            'avatar' => 'public/images/avatar.png',
        ],
    ],
    'hash' => [
        'method' => "sha256", // sha256 / md5 / ...
    ],
];
