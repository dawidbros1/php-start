<?php

declare(strict_types=1);

namespace Phantom\Model;

use Phantom\Exception\AppException;

class Route
{
    private $routes;

    public function __construct($location)
    {
        $this->routes['home'] = $location;
    }

    # Method register route on every element in $array  
    public function group(string $type, array $array)
    {
        foreach ($array as $action) {
            $this->register($type, $action);
        }
    }

    #
    public function register(string $type, string $action = "")
    {
        if ($action == "") {
            if (array_key_exists($type, $this->routes)) {
                throw new AppException("Nie może być dwóch identycznych kluczy");
            }

            $this->routes[$type] = "?type=" . $type;
        } else if ($type == "") {
            $this->routes[$type][$action] = "?action=" . $action;
        } else {
            $this->routes[$type][$action] = "?type=" . $type . "&action=" . $action;
        }
    }

    # Method returns value (address) of route like a ./?type=user&action=list
    public function get(string $path, $params = [])
    {
        $url = $this->routes;
        $array = explode(".", $path);

        if ($path == 'home') {
            $url = $this->routes['home'];
        } else if (count($array) == 1) {
            $type = $array[0];

            if (array_key_exists($type, $this->routes)) {
                $url = $this->routes[$type];
            } else {
                $action = $array[0];

                if (array_key_exists($action, $this->routes[''])) {
                    $url = $this->routes[''][$action];
                } else {
                    throw new AppException("Podany klucz routingu [ $path ] nie istnieje");
                }
            }
        } else if (count($array) == 2) {
            $type = $array[0];
            $action = $array[1];

            if ((array_key_exists($type, $this->routes)) && (array_key_exists($action, $this->routes[$type]))) {
                $url = $this->routes[$type][$action];
            } else {
                throw new AppException("Podany klucz routingu [ $path ] nie istnieje");
            }
        } else {
            throw new AppException("Podany klucz routingu [ $path ] nie istnieje");
        }

        if (!is_array($params)) {
            $params = ['id' => $params];
        }

        if (count($params)) {
            if ($path == 'home') {
                $keys = array_keys($params);
                $url = $url . "?" . $keys[0] .  '=' . $params[$keys[0]];
                array_shift($params);
            }

            foreach ($params as $name => $value) {
                $url = $url . "&" . $name .  '=' . $value;
            }
        }

        return $url;
    }
}
