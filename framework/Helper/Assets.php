<?php

declare (strict_types = 1);

namespace Phantom\Helper;

class Assets
{
    private static $location;
    public static function initConfiguration($location)
    {
        self::$location = $location;
    }

    public static function get(string $path)
    {
        return self::$location . "public/" . $path;
    }

    public static function css(string $name)
    {
        return self::$location . "public/css/" . $name . ".css";
    }

    public static function js(string $name)
    {
        return self::$location . "public/js/" . $name . ".js";
    }

    public static function images(string $path)
    {
        return self::$location . "public/images/" . $path;
    }
}
