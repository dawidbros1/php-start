<?php

declare (strict_types = 1);

$route = require_once "basic.php";

$route->group('test', "/test", [
    'list' => "/list",
    'create' => "/create",
    'show' => "/show/{id}",
    'delete' => "/delete/{id}",
    'update' => "/update/{id}",
]);

return $route;
