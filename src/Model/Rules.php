<?php

declare (strict_types = 1);

namespace App\Model;

use App\Exception\AppException;

abstract class Rules
{
    protected $rules;
    protected $defaultType = null;

    public function __construct()
    {
        $this->rules();
        $this->messages();
    }

    // Basic methods to create rules and error messages
    public function createRule(string $type, array $rules): void
    {
        foreach ($rules as $name => $value) {
            $this->rules[$type][$name]['value'] = $value;
        }
    }

    public function createMessages(string $type, array $rules): void
    {
        foreach ($rules as $name => $message) {
            $this->rules[$type][$name]['message'] = $message;
        }
    }

    // ===== ===== USE IN NameRules like AuthRules ===== =====
    public function arrayValue(string $name, bool $uppercase = false): string
    {
        $type = strtok($name, '.');
        $rule = substr($name, strpos($name, '.') + 1);
        $output = '';

        if (!is_array($this->rules[$type][$rule]['value'])) {
            throw new AppException(`Value [$name] nie jest tablicą`);
        }

        foreach ($this->rules[$type][$rule]['value'] as $value) {
            $output .= $value . ', ';
        }

        if ($uppercase) {$output = strtoupper($output);}
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

    public function setDefaultType(string $type): void
    {
        if (!$this->hasType($type)) {
            throw new AppException('Wybrany typ nie istnieje');
        }
        $this->defaultType = $type;
    }

    public function clearDefaultType(): void
    {
        $this->defaultType = null;
    }

    public function getType(?string $type = null): array
    {
        /*
        === example: ===
        input: type = username
        return username:{ rules;  messages }
         */
        if ($type === null) {
            if ($this->defaultType !== null) {
                return $this->rules[$this->defaultType];
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
        if ($this->defaultType != null) {
            $type = $this->rules[$this->defaultType];
        } elseif ($type == null) {
            throw new AppException('Typ reguły nie został wprowadzony');
        } elseif (!$this->hasType($type)) {
            throw new AppException('Wybrany typ [' . $type . '] nie istnieje');
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

    // ===== methods which use getRule() =====
    public function value(?string $name = null)
    {
        return $this->getRule($name)['value'];
    }

    public function between(string $name)
    {
        $typeName = strtok($name, '.'); // TypeName
        $limit = substr($name, strpos($name, '.') + 1); // MIN || MAX
        return $this->getRule($typeName . ".between")['value'][$limit];
    }

    public function message(?string $name = null): ?string
    {
        return $this->getRule($name)['message'];
    }

    private function getRule(string $name): array
    {
        // example: return username.rules //
        if ($this->defaultType) {
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
