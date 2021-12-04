<?php

declare (strict_types = 1);

namespace App\Helper;

class Session
{
    public static function has($name)
    {
        if (isset($_SESSION[$name]) && !empty($_SESSION[$name])) {
            return true;
        } else {
            return false;
        }

    }

    public static function get($name)
    {
        if (Session::has($name) == true) {
            return $_SESSION[$name];
        } else {
            return null;
        }

    }

    public static function getNextClear($name)
    {
        if (Session::has($name) == true) {
            $value = $_SESSION[$name];
        } else {
            $value = null;
        }

        Session::clear($name);
        return $value;
    }

    public static function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function clear($name)
    {
        unset($_SESSION[$name]);
    }
}