<?php

declare (strict_types = 1);

namespace App\Model;

abstract class Rules
{
    protected $rules;

    public function __construct()
    {
        $this->rules();
        $this->messages();
    }

    public function createRules(string $name, array $data)
    {
        foreach ($data as $key => $value) {
            $this->rules[$name][$key . '.' . 'value'] = $value;
        }
    }

    public function createMessages(string $name, array $data)
    {
        foreach ($data as $key => $message) {
            $this->rules[$name][$key . '.' . 'message'] = $message;
        }
    }

    public function getValue($rule, $name)
    {
        return $this->rules[$rule][$name . ".value"];
    }

    public function get()
    {
        return $this->rules;
    }
}