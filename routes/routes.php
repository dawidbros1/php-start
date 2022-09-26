<?php

declare (strict_types = 1);

$route = require_once "framework/routes/routes.php";

$route->group('', ['home', 'policy', 'contact', 'regulations']);
$route->group('auth', ['register', 'login', 'forgotPassword', 'resetPassword']);
$route->group('user', ['logout', 'profile', 'update']);

return $route;
