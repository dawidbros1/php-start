<?php

declare (strict_types = 1);

use Phantom\Model\Route;

$route = new Route();
$route->group('', ['home', 'policy', 'contact', 'regulations']);
$route->group('authorization', ['login', 'forgotPassword', 'resetPassword']);
$route->group('user', ['logout', 'profile', 'update']);
$route->register("registration", '');

return $route;
