<?php

declare (strict_types = 1);

namespace App\Model;

use App\Exception\AppException;

abstract class Rules
{
    protected $rules;
    protected $selectedType = null;

    public function __construct()
    {
        $this->rules();
        $this->messages();
    }

    // Podstawowe metody do tworzenia reguł oraz wiadomości
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
    }

    // ===== ===== ===== ===== =====

    public function value(?string $name = null)
    {
        return $this->getParam($name, 'value');
    }

    public function message(?string $name = null)
    {
        return $this->getParam($name, 'message');
    }

    private function getParam($name, $param)
    {
        if ($this->selectedType) {
            if (!array_key_exists($name, $this->rules[$this->selectedType])) {
                throw new AppException('Wybrana reguła nie istnieje');
            }

            $message = $this->rules[$this->selectedType][$name][$param];

        } else {
            $type = strtok($name, '.');

            if (!$this->checkType($type)) {
                throw new AppException('Wprowadzony typ reguły nie istnieje');
            }

            $rule = substr($name, strpos($name, ".") + 1);

            if (!array_key_exists($rule, $this->rules[$type])) {
                throw new AppException('Wybrana reguła nie istnieje');
            }

            $message = $this->rules[$type][$rule][$param];
        }

        return $message;
    }

    public function arrayValue(string $name, bool $uppercase = false)
    {
        $type = strtok($name, '.');
        $param = substr($name, strpos($name, ".") + 1);
        $output = "";

        foreach ($this->rules[$type][$param]['value'] as $value) {
            $output .= ($value . ", ");
        }

        if ($uppercase) {$output = strtoupper($output);}
        $output = substr($output, 0, -2);
        return $output;
    }

    // ===== ===== ===== ===== =====

    public function checkType(string $type)
    {
        if (array_key_exists($type, $this->rules)) {return true;} else {return false;}
    }

    public function selectType(string $type)
    {
        if (!$this->checkType($type)) {
            throw new AppException('Wybrany typ nie istnieje');
        }

        $this->selectedType = $type;
    }

    public function clearType()
    {
        $this->selectedType = null;
    }

    public function get(?string $type = null)
    {
        if ($this->selectedType != null) {
            return $this->rules[$this->selectedType];
        } else {
            if ($type == null) {
                throw new AppException('Typ reguły nie został wprowadzony');
            }

            if (!$this->checkType($type)) {
                throw new AppException('Wybrany typ nie istnieje');
            }

            return $this->rules[$type];
        }
    }

    public function hasKeys(array $keys, ?string $type = null)
    {
        if ($this->selectedType != null) {
            $rules = $this->rules[$this->selectedType];
        } else if ($type == null) {
            throw new AppException('Typ reguły nie został wprowadzony');
        } else if (!$this->checkType($type)) {
            throw new AppException('Wybrany typ nie istnieje');
        } else {
            $rules = $this->rules[$type];
        }

        foreach ($keys as $key) {
            if (!array_key_exists($key, $rules)) {
                return false;
            }
        }

        return true;
    }
}
