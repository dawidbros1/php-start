<?php

declare (strict_types = 1);

namespace Phantom\Model;

use Phantom\Exception\AppException;
use Phantom\Htaccess;

class Route
{
    private $routes, $htaccess, $location;

    public function __construct($location)
    {
        $this->htaccess = new Htaccess();
        $this->location = $location;
    }

    public function group(string $prefix, array $array)
    {
        foreach ($array as $action => $url) {
            $this->register($prefix, $url, $action);
        }
    }

    public function register(string $prefix, string $url, string $action = "")
    {
        $url = substr($url, 1);

        if (strlen($prefix) == 0) {
            $this->routes[$action] = $url;
            $line = "RewriteRule ^$url$ ./?action=$action [L] \n";
        }

        if (strlen($prefix) != 0 && strlen($action) == 0) {
            $this->routes[$prefix] = $url;
            $line = "RewriteRule ^$url$ ./?type=$prefix [L] \n";
        }

        if (strlen($prefix) != 0 && strlen($action) != 0) {
            $this->routes[$prefix][$action] = $url;
            $line = "RewriteRule ^$url$ ./?type=$prefix&action=$action [L] \n";
        }

        $this->htaccess->write($line);
    }

    public function homepage(string $name)
    {
        $this->routes[$name] = "";
        $this->htaccess->write("RewriteRule DirectoryIndex ./?action=home [L] \n");
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
