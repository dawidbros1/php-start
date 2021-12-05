<?php

declare (strict_types = 1);

namespace App;

class View
{
    private $user;

    public function __construct($user, $routing)
    {
        $this->user = $user;
        $this->routing = $routing;
    }

    public function render(string $page, array $params = []): void
    {
        $user = $this->user;
        $params = $this->escape($params);
        $routing = $this->routing;

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