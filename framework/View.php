<?php

declare (strict_types = 1);

namespace Phantom;

use App\Model\User;
use Phantom\Model\Route;

class View
{
    private $user, $route, $page, $params;
    private static $style = null;
    private static $title = "Brak tytułu";
    private static $location;

    public function __construct(User | null $user, Route $route, string $page, array $params)
    {
        $this->user = $user;
        $this->route = $route;
        $this->page = $page;
        $this->params = $params;
    }

    public static function initConfiguration(string $location)
    {
        self::$location = $location;
    }

    # Method set custom style, title and location
    # style: profile | registration | authorization
    # location of styles: public/css
    public static function set($data)
    {
        if (array_key_exists('style', $data)) {
            self::$style = $data['style'] ?? null;
        }

        if (array_key_exists('title', $data)) {
            self::$title = $data['title'] ?? null;
        }
    }

    # Method renders view
    public function render()
    {
        $user = $this->user;
        $route = $this->route;
        $page = $this->page;
        $style = self::$style;
        $title = self::$title;
        $location = self::$location;

        $this->params = $this->escape($this->params);

        # auto generate variables
        # from $params['name'] to $name
        foreach ($this->params as $key => $param) {
            ${$key} = $param;
        }

        require_once 'templates/layout/main.php';
    }

    # Method do htmlentities on every $params
    private function escape(array $params): array
    {
        $clearParams = [];

        foreach ($params as $key => $param) {
            switch (true) {
                case is_array($param):
                    $clearParams[$key] = $this->escape($param);
                    break;
                case gettype($param) === 'object':
                    $clearParams[$key] = $param; // skip
                    break;
                case is_int($param) || is_bool($param):
                    $clearParams[$key] = $param; // skip
                    break;
                case $param:
                    $clearParams[$key] = htmlentities($param, ENT_QUOTES);
                    break;
                default:
                    $clearParams[$key] = $param;
                    break;
            }
        }

        return $clearParams;
    }
}
