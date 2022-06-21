<?php

declare (strict_types = 1);

namespace App\Model;

use App\Validator\Validator;

abstract class Model
{
    protected static $validator = null;
    protected static $hashMethod = null;

    public static function initConfiguration($hashMethod)
    {
        if (self::$validator != null) {
            self::$validator = new Validator();
        }
        self::$hashMethod = $hashMethod;
    }

    public function update($data)
    {
        foreach (array_keys($data) as $key) {
            if (property_exists($this, $key)) {
                $this->$key = $data[$key];
            }
        }
    }

    public function getArray($array)
    {
        $properties = get_object_vars($this);

        foreach ($properties as $key => $value) {
            if (!in_array($key, $array)) {
                unset($properties[$key]);
            }
        }

        return $properties;
    }

    public function escape()
    {
        $properties = get_object_vars($this);

        foreach ($properties as $key => $value) {
            if ($value != null) {
                $this->$key = htmlentities($value);
            }

        }
    }

    public function hash($param, $method = null): string
    {
        return hash($method ?? self::$hashMethod, $param);
    }
}
