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

    // === POST ===
    public function hasPost(): bool
    {
        return !empty($this->post);
    }

    public function postParam(string $name, $default = null)
    {
        return $this->post[$name] ?? $default;
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

        if (count($this->post) != count($names)) {return false;}
        return true;
    }

    public function postParams(array $names)
    {
        $output = [];

        foreach ($names as $name) {
            $output[$name] = $this->post[$name];
        }

        return $output;
    }

    // === GET ===
    public function getParam(string $name, $default = null)
    {
        return $this->get[$name] ?? $default;
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

    // === FILES ===
    public function file(string $name, $default = null)
    {
        return $this->files[$name] ?? $default;
    }
}
