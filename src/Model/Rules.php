<?php

declare (strict_types = 1);

namespace App\Model;

abstract class Rules
{
    protected $rules;
    protected $selectedType = null;

    public function __construct()
    {
        $this->rules();
        $this->messages();
    }

    // Metoda, która dodaje reguły
    public function createRules(string $name, array $data)
    {
        foreach ($data as $key => $value) {
            $this->rules[$name][$key]['value'] = $value;
        }
    }

    // Metody, która dodaje wiadomości do reguł
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
    }

    public function value(string $name)
    {
        if (!$this->selectedType) {
            $type = strtok($name, '.');
            $param = substr($name, strpos($name, ".") + 1);
            return $this->rules[$type][$param]['value'];
        } else {
            return $this->rules[$this->selectedType][$name]['value'];
        }
    }

    public function message(string $name)
    {
        if (!$this->selectedType) {
            $type = strtok($name, '.');
            $param = substr($name, strpos($name, ".") + 1);
            return $this->rules[$type][$param]['message'];
        } else {
            return $this->rules[$this->selectedType][$name]['message'];
        }
    }

    public function arrayValue(string $name, bool $uppercase = false)
    {
        $type = strtok($name, '.');
        $param = substr($name, strpos($name, ".") + 1);
        $output = "";

        foreach ($this->rules[$type][$param]['value'] as $value) {
            $output .= ($value . ", ");
        }

        if ($uppercase) {
            $output = strtoupper($output);
        }

        $output = substr($output, 0, -2);

        return $output;
    }

    public function get()
    {
        return $this->rules;
    }

    public function selectType($type)
    {
        $this->selectedType = $type;
    }

    public function getByType($type)
    {
        return $this->rules[$type];
    }
}
