<?php

declare (strict_types = 1);

use Phantom\Model\Route;

$route = new Route($location);

$route->homepage('home');

$route->group('', [
    'policy' => "/policy",
    'contact' => "/contact",
    'regulations' => "/regulations",
]);

$route->register("registration", "/register");
$route->register("authorization", "/login");

$route->group('user', [
    'logout' => "/logout",
    'profile' => "/user/profile",
    'update' => "/user/profile/update",
]);

$route->group('passwordRecovery', [
    'forgot' => "/password/forgot",
    'reset' => "/password/reset",
]);

// $route->group('test', ['select']); // For test

return $route;
