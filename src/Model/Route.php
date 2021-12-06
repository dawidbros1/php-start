<?php

declare (strict_types = 1);

namespace App\Model;

class Route
{
    private $routes;

    public function group(string $prefix, array $names)
    {
        if (strlen($prefix) == 0) {
            foreach ($names as $name) {
                $this->routes[$name] = "?action=" . $name;
            }
        } else {
            foreach ($names as $name) {
                $this->routes[$prefix . "." . $name] = "?type=$prefix&action=$name";
            }
        }
    }

    public function get()
    {
        return $this->routes;
    }
}