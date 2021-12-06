<?php

declare (strict_types = 1);

namespace App\Model;

class Rules
{
    private $rules;

    public function create(string $name, array $data)
    {
        foreach ($data as $key => $item) {
            $this->rules[$name] = $data;
        }
    }

    public function get()
    {
        return $this->rules;
    }
}