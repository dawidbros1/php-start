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

    # Method register route on every element in $array
    # array $array: [ 'action' => 'url', 'action' => 'url' ]
    # Example: $array = [
    #   'profile' => '/user/profile',
    #   'list' => '/users/list'
    # ]
    public function group(string $name, array $array)
    {
        foreach ($array as $action => $url) {
            $this->register($name, $url, $action);
        }
    }

    #string $name: It is controller name like a user|category. If is empty will be runs default type (general)
    #string $url: It is url which will be see on address bar. Example: user/profile | users/list
    #string $action: Which action from controller will be runs. If $action is empty will be run method index()
    public function register(string $name, string $url, string $action = "")
    {
        $url = substr($url, 1);
        $fullUrl = $this->location . $url;

        if (strlen($name) == 0) {
            $this->routes[$action] = $fullUrl;
            $line = "RewriteRule ^$url$ ./?action=$action";
        }

        if (strlen($name) != 0 && strlen($action) == 0) {
            $this->routes[$name] = $fullUrl;
            $line = "RewriteRule ^$url$ ./?type=$name";
        }

        if (strlen($name) != 0 && strlen($action) != 0) {
            $this->routes[$name][$action] = $fullUrl;
            $line = "RewriteRule ^$url$ ./?type=$name&action=$action";
        }

        /* auto fill file .htaccess */
        $this->htaccess->write($line);
    }

    # Special route for home page without setting $name and $action
    public function homepage(string $name)
    {
        $this->routes[$name] = $this->location;
        $this->htaccess->write("RewriteRule DirectoryIndex .");
    }

    # Method returns value (address) of route like a ./?type=user&action=list
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
