<?php

declare (strict_types = 1);

namespace Phantom\Model;

use Phantom\Exception\AppException;
use Phantom\Htaccess;

class Route
{
    private $routes, $htaccess, $location;

    public function __construct(string $location)
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
        $fullUrl = $this->location . $url;

        if (strlen($prefix) == 0) {
            $this->routes[$action] = $fullUrl;
            $line = "RewriteRule ^$url$ ./?action=$action";
        }

        if (strlen($prefix) != 0 && strlen($action) == 0) {
            $this->routes[$prefix] = $fullUrl;
            $line = "RewriteRule ^$url$ ./?type=$prefix";
        }

        if (strlen($prefix) != 0 && strlen($action) != 0) {
            $this->routes[$prefix][$action] = $fullUrl;
            $line = "RewriteRule ^$url$ ./?type=$prefix&action=$action";
        }

        $this->htaccess->write($line);
    }

    public function homepage(string $name)
    {
        $this->routes[$name] = $this->location;
        $this->htaccess->write("RewriteRule DirectoryIndex ./?action=home");
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
