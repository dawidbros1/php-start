<?php

declare(strict_types=1);

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
    public function createRules(string $type, array $rules)
    {
        foreach ($rules as $name => $value) {
            $this->rules[$type][$name]['value'] = $value;
        }
    }

    public function createMessages(string $type, array $rules)
    {
        foreach ($rules as $name => $message) {
            if ($name == 'between') {
                $this->rules[$type]['min']['message'] = $message;
                $this->rules[$type]['max']['message'] = $message;
            } else {
                $this->rules[$type][$name]['message'] = $message;
            }
        }
    }

    // ===== ===== USE FROM NameRules like AuthRules ===== =====
    public function value(?string $name = null)
    {
        return $this->getRule($name)['value'];
    }

    public function message(?string $name = null)
    {
        return $this->getRule($name)['message'];
    }

    public function arrayValue(string $name, bool $uppercase = false)
    {
        $type = strtok($name, '.');
        $rule = substr($name, strpos($name, '.') + 1);
        $output = '';

        foreach ($this->rules[$type][$rule]['value'] as $value) {
            $output .= $value . ', ';
        }

        if ($uppercase) {
            $output = strtoupper($output);
        }
        $output = substr($output, 0, -2);
        return $output;
    }

    // ===== ===== ===== ===== =====
    public function hasType(string $type)
    {
        if (array_key_exists($type, $this->rules)) {
            return true;
        } else {
            return false;
        }
    }

    public function selectType(string $type)
    {
        if (!$this->hasType($type)) {
            throw new AppException('Wybrany typ nie istnieje');
        }
        $this->selectedType = $type;
    }

    public function clearType()
    {
        $this->selectedType = null;
    }

    public function getType(?string $type = null)
    {
        if ($type === null) {
            if ($this->selectedType !== null) {
                return $this->rules[$this->selectedType];
            } else {
                throw new AppException('Typ reguły nie został wprowadzony');
            }
        } else {
            if (!$this->hasType($type)) {
                throw new AppException('Wybrany typ nie istnieje');
            } else {
                return $this->rules[$type];
            }
        }
    }

    public function hasKeys(array $keys, ?string $type = null)
    {
        if ($this->selectedType != null) {
            $rules = $this->rules[$this->selectedType];
        } elseif ($type == null) {
            throw new AppException('Typ reguły nie został wprowadzony');
        } elseif (!$this->hasType($type)) {
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

    // ===== ===== ===== ===== =====

    private function getRule(string $name)
    {
        if ($this->selectedType) {
            if (!array_key_exists($name, $this->rules[$this->selectedType])) {
                throw new AppException('Wybrana reguła nie istnieje');
            }

            $message = $this->rules[$this->selectedType][$name];
        } else {
            $type = strtok($name, '.');

            if (!$this->hasType($type)) {
                throw new AppException('Wprowadzony typ reguły nie istnieje');
            }

            $rule = substr($name, strpos($name, '.') + 1);

            if (!array_key_exists($rule, $this->rules[$type])) {
                throw new AppException('Wybrana reguła nie istnieje');
            }

            $message = $this->rules[$type][$rule];
        }

        return $message;
    }
}
