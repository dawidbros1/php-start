<?php

declare (strict_types = 1);

use Phantom\Model\Config;

return new Config(
    [
        'project' => [
            'location' => "domain + folder if needs",
        ],
        'db' => [
            'host' => 'localhost',
            'database' => '',
            'user' => '',
            'password' => '',
        ],
        'mail' => [
            'email' => 'websideEmail@domaina.com',
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
            'route' => [
                'home' => 'home', // page after login
                'logout' => 'authorization', // page after logout
            ],
            'hash' => [
                'method' => 'sha256', // sha25 || md5 ...
            ],
        ],
        'reCAPTCHA' => [
            'key' => [
                'side' => '',
                'secret' => '',
            ],
        ],
    ]
);
