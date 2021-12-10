<?php

declare (strict_types = 1);

use App\Model\Route;

$route = new Route();
$route->group('', ['home', 'policy', 'contact', 'regulations']);
$route->group('auth', ['register', 'login']);
$route->group('user', ['logout', 'profile', 'updateUsername', 'updatePassword', 'uploadAvatar']);

return $route->get();
