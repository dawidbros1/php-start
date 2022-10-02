<?php

declare (strict_types = 1);

namespace App;

class View
{
    private $user, $route;
    private static $style = null;
    private static $title = "Brak tytułu";

    public static function set($data)
    {
        if (array_key_exists('style', $data)) {
            self::$style = $data['style'] ?? null;
        }

        if (array_key_exists('title', $data)) {
            self::$title = $data['title'] ?? null;
        }
    }

    public function __construct($user, $route)
    {
        $this->user = $user;
        $this->route = $route;
    }

    public function render(string $page, array $params = []): void
    {
        $user = $this->user;
        $route = $this->route;
        $style = self::$style;
        $title = self::$title;

        $params = $this->escape($params);

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
                case gettype($param) === 'object':
                    $clearParams[$key] = $param; // simple type: 'key' => 'value'
                    // $clearParams[$key] = $param->escape(); // simple type: 'key' => 'value'

                    // foreach ($param as $key2 => $value) {
                    //     if ($key2 == "fillable") {
                    //         continue;
                    //     }

                    //     if (gettype($value) === "array" || gettype($value) === "object") {
                    //         $clearParams[$key]->$key2 = $this->escape($value); // complex type
                    //     }
                    // }
                    break;
                case is_int($param) || is_bool($param):
                    $clearParams[$key] = $param;
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
