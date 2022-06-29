<?php

declare (strict_types = 1);

namespace App\Model;

use App\Validator\Validator;

abstract class Model
{
    protected static $validator = null;
    protected static $hashMethod = null;
    protected $rules;

    public static function initConfiguration($hashMethod)
    {
        self::$validator = new Validator();
        self::$hashMethod = $hashMethod;
    }

    public function find($value, $column = "id")
    {
        if ($data = $this->repository->get($value, $column)) {
            $this->update($data);
            return $this;
        }

        return null;
    }

    protected function validate($data)
    {
        return self::$validator->validate($data, $this->rules);
    }

    protected function validateImage($FILE, $type)
    {
        return self::$validator->validateImage($FILE, $this->rules, $type);
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
            if ($key === "rules" || $key === "repository") {
                continue;
            }

            if ($value != null) {
                $this->$key = htmlentities($value);
            }
        }
    }

    public function hash($param, $method = null): string
    {
        return hash($method ?? self::$hashMethod, $param);
    }

    public function hashFile($file)
    {
        $type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name = $this->hash(date('Y-m-d H:i:s') . "_" . $file['name']);
        $file['name'] = $name . '.' . $type;
        return $file;
    }

    protected function uploadFile($path, $FILE): bool
    {
        $target_dir = $path;
        $type = strtolower(pathinfo($FILE['name'], PATHINFO_EXTENSION));
        $target_file = $target_dir . basename($FILE["name"]);

        if (move_uploaded_file($FILE["tmp_name"], $target_file)) {
            return true;
        } else {
            Session::set('error', 'Przepraszamy, wystąpił problem w trakcie wysyłania pliku');
            return false;
        }
    }
}
