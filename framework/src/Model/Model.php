<?php

declare (strict_types = 1);

namespace Phantom\Model;

use Phantom\Helper\Session;
use Phantom\Validator\Validator;

abstract class Model
{
    protected static $validator = null;
    protected static $hashMethod = null;
    protected $rules;
    protected $repository;
    protected $fillable;
    public static function initConfiguration(Config $config)
    {
        self::$validator = new Validator();
        self::$hashMethod = $config->get("default.hash.method");
    }

    public function __construct(array $data = [], bool $onlyFillable = false)
    {
        if ($onlyFillable == false) {
            $namaspace = $this->getNamespace(true);

            $rules = $namaspace[0] . "\Rules\\" . $namaspace[2] . "Rules";
            $repository = $namaspace[0] . "\Repository\\" . $namaspace[2] . "Repository";

            $this->rules = new $rules;
            $this->repository = new $repository;
        }

        $this->set($data);
    }

    public function set($data)
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->$key = $value;
            }
        }
    }

    public function find(array $input, string $options = "", bool $onlyFillable = false)
    {
        if ($data = $this->repository->get($input, $options)) {
            return $this->createObject($data, $onlyFillable);
        }

        return null;
    }

    public function findById($id)
    {
        return $this->find(['id' => $id]);
    }

    public function findAll(array $input, string $options = "", bool $onlyFillable = true)
    {
        $output = [];
        $data = $this->repository->getAll($input, $options);

        if ($data) {
            foreach ($data as $item) {
                $output[] = $this->createObject($item, $onlyFillable);
            }
        }

        return $output;
    }

    private function createObject($data, $onlyFillable)
    {
        $namaspace = $this->getNamespace();
        return new $namaspace($data, $onlyFillable);
    }

    // Validation
    protected function validate($data)
    {
        return self::$validator->validate($data, $this->rules);
    }

    protected function validateImage($FILE, $type)
    {
        return self::$validator->validateImage($FILE, $this->rules, $type);
    }

    // CRUD
    public function create(array $data, $validate = true)
    {
        $data['user_id'] = User::ID();

        if (($validate === true && $this->validate($data)) || $validate === false) {
            $this->set($data);
            $this->repository->create($this);
            return true;
        }

        return false;
    }

    public function update(array $data, array $toUpdate = [], $validate = true)
    {
        if (($validate === true && $this->validate($data)) || $validate === false) {
            $this->set($data);

            if (empty($toUpdate)) {
                $data = $this->getArray($this->fillable);
            } else {
                $data = $this->getArray(['id', ...$toUpdate]);
            }

            $this->escape();
            $this->repository->update($data);
            Session::success('Dane zostały zaktualizowane'); // Default value
            return true;
        }
        return false;
    }

    public function delete(?int $id = null)
    {
        if ($id !== null) {
            $this->repository->delete((int) $id);
        } else {
            $this->repository->delete((int) $this->id);
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

    private function getClassName()
    {
        $path = explode('\\', get_class($this));
        $className = array_pop($path);
        return $className;
    }

    private function getNamespace(bool $toArray = false): array | string
    {
        $r = new \ReflectionClass($this);
        $namaspace = $r->getName();

        if ($toArray == true) {
            $namaspace = explode("\\", $r->getName());
        }

        /*
        0 => [ App | Phantom]
        1 => Model
        2 => Name of model
         */

        return $namaspace;
    }
}
