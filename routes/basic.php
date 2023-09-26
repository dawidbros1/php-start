<?php

declare(strict_types=1);

use Phantom\Model\Route;

$route = new Route($location);

$route->group("", ['policy', 'contact', 'regulations']);

$route->register("registration");
$route->register("authorization");

$route->group('user', ['logout', 'profile', 'update']);
$route->group('passwordRecovery', ['forgot', 'reset']);

// dump($route);
// die();

return $route;
