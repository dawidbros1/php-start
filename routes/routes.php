<?php

declare (strict_types = 1);

$route = require_once "framework/routes/routes.php";

$route->register("example", 'test');

return $route;
