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

    // === GENERAL ===

    public function param(string $name, $default = null)
    {
        if ($this->isPost()) {
            return $this->postParam($name, $default);
        } else {
            return $this->getParam($name, $default);
        }
    }

    // === POST ===
    public function hasPost(): bool
    {
        return !empty($this->post);
    }

    public function postParam(string $name, $default = null)
    {
        return $this->post[$name] ?? $default;
    }

    public function postParams(array $names)
    {
        $output = [];

        foreach ($names as $name) {
            $output[$name] = $this->postParam($name);
        }

        return $output;
    }

    public function hasPostName(string $name)
    {
        if (!isset($this->post[$name])) {return false;}
        return true;
    }

    public function hasPostNames(array $names)
    {
        foreach ($names as $name) {
            if (!isset($this->post[$name])) {
                return false;
            }
        }

        return true;
    }

    // === GET ===
    public function getParam(string $name, $default = null)
    {
        return $this->get[$name] ?? $default;
    }

    public function getParams(array $names)
    {
        $output = [];

        foreach ($names as $name) {
            $output[$name] = $this->getParam($name);
        }

        return $output;
    }

    // === SERVER ===
    public function isPost(): bool
    {
        return $this->server['REQUEST_METHOD'] === 'POST';
    }

    public function isGet(): bool
    {
        return $this->server['REQUEST_METHOD'] === 'GET';
    }

    public function queryString(): string
    {
        return $this->server['QUERY_STRING'];
    }
    // === FILES ===
    public function file(string $name, $default = null)
    {
        return $this->files[$name] ?? $default;
    }
}
