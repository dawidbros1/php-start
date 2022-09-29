<?php

declare (strict_types = 1);

use Phantom\Model\Route;

$route = new Route();
$route->group('', ['home', 'policy', 'contact', 'regulations']);

$route->register("registration");
$route->register("authorization");
$route->group('user', ['logout', 'profile', 'update']);
$route->group('passwordRecovery', ['forgot', 'reset']);

$route->group('test', ['select']); // For test

return $route;
