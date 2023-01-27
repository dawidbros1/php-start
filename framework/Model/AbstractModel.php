<?php

declare(strict_types=1);

namespace Phantom\Model;

use App\Repository\UserRepository;
use Phantom\Helper\Session;
use Phantom\Query\Finder;
use Phantom\Query\QueryModel;
use Phantom\Repository\StdRepository;

abstract class AbstractModel
{
    protected static $hashMethod = null;
    protected static $config;
    protected $table;

    public static function initConfiguration(Config $config)
    {
        self::$config = $config;
        self::$hashMethod = $config->get("default.hash.method");
    }

    public function __construct(array $data = [])
    {
        $this->set($data);
    }

    public function set($data)
    {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $method = $this->convertToCamelCase('set' . ucfirst($key));
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
    }

    // # Method return values of a lot of properties
    public function _getData(array $properties = [])
    {
        if (empty($properties)) {
            $methods = get_class_methods($this::class);

            foreach ($methods as $method) {
                if (substr($method, 0, 3) === 'get') {
                    $key = lcfirst(substr($method, 3));
                    $key = $this->convertToSnakeCase($key);
                    $output[$key] = $this->$method();
                }
            }
        } else {
            $properties[] = "id";

            foreach ($properties as $name) {
                $method = 'get' . $this->convertToCamelCase($name);

                if (method_exists($this, $method)) {
                    $output[$name] = $this->$method();
                }
            }
        }

        return $output ?? [];
    }

    # Method do htmlentities on every property
    public function escape()
    {
        $properties = get_object_vars($this);

        foreach ($properties as $index => $key) {
            if (property_exists($this, $key)) {
                $this->$key = htmlentities((string) $this->$key);
            }
        }
    }

    # Method hash parameter
    public function hash($param, $method = null): string
    {
        return hash($method ?? self::$hashMethod, $param);
    }

    // # Method hash name of file to create unique file name
    public function hashFile($file)
    {
        $type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name = hash('sha256', date('Y-m-d H:i:s') . "_" . $file['name']);
        $file['name'] = $name . '.' . $type;
        return $file;
    }

    # Method upload file on server
    protected function uploadFile($path, $FILE): bool
    {
        $location = $path . basename($FILE["name"]);

        if (move_uploaded_file($FILE["tmp_name"], $location)) {
            return true;
        } else {
            Session::set('error', 'Przepraszamy, wystąpił problem w trakcie wysyłania pliku');
            return false;
        }
    }

    # $url - Project location as "/.."
    public function _getLocation()
    {
        # Project location => http://localhost/php-start/
        $location = self::$config->get('project.location');

        # Current URL => http://localhost/php-start/user/profile/update
        $url = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        # diff => user/profile/update
        $diff = str_replace($location, "", $url);

        $array = explode("/", $diff);
        $array = array_map(fn($element) => "/..", $array);
        array_pop($array); # We needs pop one element
        $output = implode("", $array);
        $output = ".$output/";

        return $output;
    }

    # Method checks if email exists and return status
    public function existsEmail($email)
    {
        return in_array($email, (new UserRepository())->getEmails());
    }

    private function convertToSnakeCase($string)
    {
        $string = preg_replace('/([A-Z])/', '_$1', $string);
        return strtolower($string);
    }

    private function convertToCamelCase($string)
    {
        $string = ucwords($string, '_');
        return str_replace('_', '', $string);
    }

    # Method adds record to database
    # if object was validated earlier we can skip validate in this method
    public function create()
    {
        (new StdRepository($this->table))->create($this);
    }

    # Method updates current object | we can skip validate
    # array $toValidate: which properties will be validate
    public function update(string|array $update = [])
    {
        if (!is_array($update)) {
            $copy = $update;
            $update = [];
            $update[0] = $copy;
        }

        $this->escape();
        (new StdRepository($this->table))->update($this, $update);
        Session::success('Dane zostały zaktualizowane'); // Default value
        return true;
    }

    # Method delete record from database
    # if property ID is sets this record will we deleted
    # else current object will be deleted
    public function delete(?int $id = null)
    {
        $repository = new StdRepository($this->table);

        if ($id !== null) {
            $repository->delete((int) $id);
        } else {
            $repository->delete((int) $this->getId());
        }
    }

    // abstract method
    public abstract function getId();
}