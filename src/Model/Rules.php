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
    public function createRule(string $type, array $rules): void
    {
        foreach ($rules as $name => $value) {
            $this->rules[$type][$name]['value'] = $value;
        }
    }

    public function createMessages(string $type, array $rules): void
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

    // ===== ===== USE IN NameRules like AuthRules ===== =====
    public function value(?string $name = null)
    {
        return $this->getRule($name)['value'];
    }

    public function message(?string $name = null): string
    {
        return $this->getRule($name)['message'];
    }

    public function arrayValue(string $name, bool $uppercase = false): string
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
    public function hasType(string $type): bool
    {
        if (array_key_exists($type, $this->rules)) {
            return true;
        } else {
            return false;
        }
    }

    public function selectType(string $type): void
    {
        if (!$this->hasType($type)) {
            throw new AppException('Wybrany typ nie istnieje');
        }
        $this->selectedType = $type;
    }

    public function clearType(): void
    {
        $this->selectedType = null;
    }

    public function getType(?string $type = null): array
    {
        if ($type === null) {
            if ($this->selectedType !== null) {
                return $this->rules[$this->selectedType];
            } else {
                throw new AppException('Typ reguły nie został wprowadzony');
            }
        } else {
            if (!$this->hasType($type)) {
                throw new AppException('Wybrany typ [' . $type . '] nie istnieje');
            } else {
                return $this->rules[$type];
            }
        }
    }

    public function typeHasRules(array $rules, ?string $type = null): bool
    {
        if ($this->selectedType != null) {
            $type = $this->rules[$this->selectedType];
        } elseif ($type == null) {
            throw new AppException('Typ reguły nie został wprowadzony');
        } elseif (!$this->hasType($type)) {
            throw new AppException('Wybrany typ nie istnieje');
        } else {
            $type = $this->rules[$type];
        }

        foreach ($rules as $rule) {
            if (!array_key_exists($rule, $type)) {
                return false;
            }
        }

        return true;
    }

    // ===== ===== ===== ===== =====

    private function getRule(string $name): array
    {
        if ($this->selectedType) {
            return $this->getType()[$name]; // Name like a min | max
        } else {
            $typeName = strtok($name, '.');
            $ruleName = substr($name, strpos($name, '.') + 1);

            $type = $this->getType($typeName); // Name like a password.min | password.max

            if ($this->typeHasRules([$ruleName], $typeName)) {
                return $type[$ruleName];
            } else {
                throw new AppException('Wybrana reguła nie istnieje');
            }
        }
    }
}
