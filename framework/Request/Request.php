<?php

declare (strict_types = 1);

namespace Phantom\Request;

class Request extends PostRequest
{
    protected $get = [];
    protected $post = [];
    protected $server = [];
    protected $files = [];

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
    }

    public function input($name, $method = "POST", $default = null)
    {
        if ($method == "POST") {
            return $this->post[$name] ?? $default;
        } else if ($method == "GET") {
            return $this->get[$name] ?? $default;
        }
    }

    # Method returns value of parameter from request for current request method
    public function param(string $name, $default = null)
    {
        if ($this->isPost()) {
            return $this->postParam($name, $default);
        } else {
            return $this->getParam($name, $default);
        }
    }

    # Method returns QUERY_STRING
    public function queryString(): string
    {
        return $this->server['QUERY_STRING'];
    }

    # Method returns file
    public function file(string $name, $default = null)
    {
        return $this->files[$name] ?? $default;
    }

    # Method returns current url
    public function url()
    {
        return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }

    # Method returns url to last page
    public function lastPage()
    {
        return $this->server['HTTP_REFERER'];
    }
}
