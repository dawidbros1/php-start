<?php

declare (strict_types = 1);

namespace App\Helper;

class Request
{
    private $get = [];
    private $post = [];
    private $server = [];
    private $files = [];

    public function __construct(array $get, array $post, array $server, array $files)
    {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
        $this->files = $files;
    }

    // =============== GENERAL =============== //
    public function param(string $name, $default = null)
    {
        if ($this->isPost()) {
            return $this->postParam($name, $default);
        } else {
            return $this->getParam($name, $default);
        }
    }

    // =============== POST =============== //
    public function isPost(array $names = [])
    {
        if ($status = $this->server['REQUEST_METHOD'] === 'POST') {
            if (!empty($names) && ($status = $this->hasPostNames($names, false))) {
                if (count($data = $this->postParams($names)) == 1) {
                    return $data[$names[0]];
                } else {
                    return $data;
                }
            }
        }
        return $status;
    }

    public function postParams(array $names)
    {
        foreach ($names as $name) {
            $output[$name] = $this->postParam($name);
        }

        return $output ?? [];
    }

    public function postParam(string $name, $default = null)
    {
        return $this->post[$name] ?? $default;
    }

    public function hasPostNames(array $names, bool $returnData = true)
    {
        foreach ($names as $name) {
            if ($this->hasPostName($name, false) === false) {
                return false;
            }
        }

        return $returnData ? $this->postParams($names) : true;
    }

    public function hasPostName(string $name, bool $returnData = true)
    {
        if (!isset($this->post[$name])) {return false;}
        return $returnData ? $this->postParam($name) : true;
    }

    // =============== GET =============== //
    public function isGet(array $names = [])
    {
        if ($status = $this->server['REQUEST_METHOD'] === 'GET') {
            if (!empty($names) && ($status = $this->hasGetNames($names, false))) {
                if (count($data = $this->getParams($names)) == 1) {
                    return $data[$names[0]];
                } else {
                    return $data;
                }
            }
        }
        return $status;
    }

    public function getParams(array $names)
    {
        foreach ($names as $name) {
            $output[$name] = $this->getParam($name);
        }

        return $output ?? [];
    }

    public function getParam(string $name, $default = null)
    {
        return $this->get[$name] ?? $default;
    }

    public function hasGetNames(array $names, bool $returnData = true)
    {
        foreach ($names as $name) {
            if ($this->hasGetName($name, false) === false) {
                return false;
            }
        }

        return $returnData ? $this->getParams($names) : true;
    }

    public function hasGetName(string $name, bool $returnData = true)
    {
        if (!isset($this->get[$name])) {return false;}
        return $returnData ? $this->getParam($name) : true;
    }

    // =============== Other ===============
    public function queryString(): string
    {
        return $this->server['QUERY_STRING'];
    }

    public function file(string $name, $default = null)
    {
        return $this->files[$name] ?? $default;
    }

    public function url()
    {
        return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }
}
