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

    public function __construct(User | null $user, Route $route, string $page, array $params)
    {
        $this->user = $user;
        $this->route = $route;
        $this->page = $page;
        $this->params = $params;
    }

    public static function set($data)
    {
        if (array_key_exists('style', $data)) {
            self::$style = $data['style'] ?? null;
        }

        if (array_key_exists('title', $data)) {
            self::$title = $data['title'] ?? null;
        }
    }

    public function render()
    {
        $user = $this->user;
        $route = $this->route;
        $page = $this->page;
        $style = self::$style;
        $title = self::$title;

        foreach ($this->params as $key => $param) {
            ${$key} = $param;
        }

        require_once 'templates/layout/main.php';
    }

    private function escape(array $params): array
    {
        $clearParams = [];
        foreach ($params as $key => $param) {

            if (gettype($params === "object")) {
                $clearParams[$key] = $param;
                continue;
            }

            switch (true) {
                case is_array($param):
                    $clearParams[$key] = $this->escape($param);
                    break;
                case is_int($param):
                    $clearParams[$key] = $param;
                    break;
                case $param:
                    $clearParams[$key] = htmlentities($param);
                    break;
                default:
                    $clearParams[$key] = $param;
                    break;
            }
        }

        return $clearParams;
    }
}
