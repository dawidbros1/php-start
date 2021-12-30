<?php

declare (strict_types = 1);

use App\Model\Route;

$route = new Route();
$route->group('', ['home', 'policy', 'contact', 'regulations']);
$route->group('auth', ['register', 'login', 'forgotPassword', 'resetPassword']);
$route->group('user', ['logout', 'profile', 'update']);

return $route;
