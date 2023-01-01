<?php

declare (strict_types = 1);

use Phantom\Model\Route;

$route = new Route($location);

$route->homepage('home');

$route->group("", "", [#
    'policy' => "/policy",
    'contact' => "/contact",
    'regulations' => "/regulations",
]);

$route->group('auth', "/auth", [
    'register' => "/register",
    'login' => "/login",
]);

$route->group('user', "/user", [# controller: UserController | url_prefix: /user
    'logout' => "/logout",
    'profile' => "/profile", # action: profile on url .../user/profile
    'update' => "/profile/update",
]);

$route->group('passwordRecovery', "/password", [
    'forgot' => "/forgot",
    'reset' => "/reset",
]);

return $route;
