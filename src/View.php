<?php

declare (strict_types = 1);

namespace App;

class View
{
    private $user;

    public function __construct($user, $route)
    {
        $this->user = $user;
        $this->route = $route;
    }

    public function render(string $page, array $params = [], ?array $styles = []): void
    {
        $user = $this->user;
        $params = $this->escape($params);
        $route = $this->route;
        $styles = $styles;

        require_once 'templates/layout/main.php';
    }

    private function escape(array $params): array
    {
        $clearParams = [];
        foreach ($params as $key => $param) {
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