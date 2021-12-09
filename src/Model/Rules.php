<?php

declare (strict_types = 1);

namespace App\Model;

abstract class Rules
{
    protected $rules;
    protected $types;

    public function __construct()
    {
        $this->rules();
        $this->types = array_keys($this->rules);
        $this->messages();
    }

    public function createRules(string $name, array $data)
    {
        foreach ($data as $key => $value) {
            $this->rules[$name][$key]['value'] = $value;
        }
    }

    public function createMessages(string $name, array $data)
    {
        foreach ($data as $key => $message) {

            if ($key == "between") {
                $this->rules[$name]['min']['message'] = $message;
                $this->rules[$name]['max']['message'] = $message;
            } else {
                $this->rules[$name][$key]['message'] = $message;
            }
        }

        array_shift($this->types);
    }

    public function getValue($name)
    {
        $type = $this->types[0];
        return $this->rules[$type][$name]['value'];
    }

    public function get()
    {
        return $this->rules;
    }
}
