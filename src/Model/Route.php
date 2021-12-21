<?php

declare (strict_types = 1);

namespace App\Model;

use App\Exception\AppException;

class Route
{
    private $routes;

    public function group(string $prefix, array $names)
    {
        foreach ($names as $name) {
            $this->register($prefix, $name);
        }
    }

    public function register(string $prefix, string $name)
    {
        if (strlen($prefix) == 0) {
            $this->routes[$name] = "?action=" . $name;
        } else {
            $this->routes[$prefix][$name] = "?type=$prefix&action=$name";
        }
    }

    public function get($path)
    {
        $output = $this->routes;
        $array = explode(".", $path);

        foreach ($array as $name) {

            if (array_key_exists($name, $output)) {
                $output = $output[$name];
            } else {
                throw new AppException("Podany klucz routingu [ $path ] nie istnieje");
            }
        }

        return $output;
    }
}
