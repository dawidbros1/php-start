<?php

declare (strict_types = 1);

namespace Phantom\Model;

use Phantom\Exception\AppException;
use Phantom\Helper\Session;
use Phantom\Validator\Validator;

abstract class Model
{
    protected static $validator = null;
    protected static $hashMethod = null;
    protected static $config;
    protected $rules;
    protected $repository;
    public $fillable;
    public static function initConfiguration(Config $config)
    {
        self::$validator = new Validator();
        self::$config = $config;
        self::$hashMethod = $config->get("default.hash.method");
    }
    /* rulesitory => Rules and Repository */
    public function __construct(array $data = [], bool $rulesitory = true)
    {
        if ($rulesitory == true) {
            $namaspace = explode("\\", $this::class);

            $rules = $namaspace[0] . "\Rules\\" . $namaspace[2] . "Rules";
            $repository = $namaspace[0] . "\Repository\\" . $namaspace[2] . "Repository";

            $this->rules = new $rules;
            $this->repository = new $repository;
        }

        $this->setArray($data);
    }

    public function set($property, $value)
    {
        if ($this->propertyExists($property)) {
            $this->$property = $value;
        }
    }

    public function setArray($data)
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->$key = $value;
            }
        }
    }

    public function get($property)
    {
        if ($this->propertyExists($property)) {
            return $this->$property;
        }
    }

    // OTHER
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

    /* Method starts data validation */
    protected function validate($data)
    {
        return self::$validator->validate($data, $this->rules);
    }

    /* Method starts image validation */
    protected function validateImage($FILE, $type)
    {
        return self::$validator->validateImage($FILE, $this->rules, $type);
    }

    // DATABASE => FIND
    public function find(array $input, string $options = "", bool $rulesitory = true, $namaspace = null)
    {
        if ($namaspace == null) {
            $namaspace = $this::class;
        }

        if ($data = $this->repository->get($input, $options)) {
            return new $namaspace($data, $rulesitory);
        }

        return null;
    }

    public function findById($id, string $options = "", bool $rulesitory = true, $namaspace = null)
    {
        return $this->find(['id' => $id], $options, $rulesitory, $namaspace);
    }

    public function findAll(array $input, string $options = "", bool $rulesitory = true, $namaspace = null)
    {
        $output = [];
        $data = $this->repository->getAll($input, $options);

        if ($namaspace == null) {
            $namaspace = $this::class;
        }

        if ($data) {
            foreach ($data as $item) {
                $output[] = new $namaspace($item, $rulesitory);
            }
        }

        return $output;
    }

    // DATABASE => SAVE
    public function create($validate = true)
    {
        if (($validate === true && $this->validate($this)) || $validate === false) {
            $this->repository->create($this);
            return true;
        }

        return false;
    }

    public function update($toUpdate = [], $validate = true)
    {
        $data = $this->getArray($toUpdate);

        if (($validate === true && $this->validate($data)) || $validate === false) {
            $this->escape();
            $this->repository->update($this);
            Session::success('Dane zostały zaktualizowane'); // Default value
            return true;
        }
        return false;
    }

    // DATABASE => DELETE
    public function delete(?int $id = null)
    {
        if ($id !== null) {
            $this->repository->delete((int) $id);
        } else {
            $this->repository->delete((int) $this->id);
        }
    }

    // OTHER
    public function escape()
    {
        foreach ($this->fillable as $index => $key) {
            if (property_exists($this, $key)) {
                $this->$key = htmlentities((string) $this->$key);
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

    private function propertyExists($name)
    {
        $properties = get_object_vars($this);

        if (array_key_exists($name, (array) $properties)) {
            return true;
        } else {
            throw new AppException("Property [" . $name . "] doesn't exists");
        }
    }
}
