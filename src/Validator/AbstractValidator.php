<?php

declare (strict_types = 1);

namespace App\Validator;

use App\Helper\Session;

abstract class AbstractValidator
{
    protected static $betweenStatus = false;

    // Metody walidacyjne wielokrotnego użytku
    protected function strlenBetween(string $variable, int $min, int $max)
    {
        if (strlen($variable) > $min && strlen($variable) < $max) {
            return true;
        }

        return false;
    }

    protected function strlenMax(string $variable, int $max)
    {
        if (strlen($variable) > $max) {
            return false;
        }

        return true;
    }

    protected function strlenMin(string $variable, int $min)
    {
        if (strlen($variable) < $min) {
            return false;
        }

        return true;
    }

    // Ogólna klasa VALIDATORA

    protected function validate(array $data)
    {
        $types = array_keys($data);

        if (array_key_exists('password', $data) && array_key_exists('repeat_password', $data)) {
            if ($data['password'] != $data['repeat_password']) {
                Session::set("error:password:same", "Hasła nie są jednakowe");
                $ok = false;
            }
        }

        foreach ($types as $type) {
            if (!$this->rules->checkType($type)) {continue;}

            self::$betweenStatus = false;

            $rules = $this->rules->get($type);
            $this->rules->selectType($type);

            $between = (bool) (array_key_exists('min', $rules) && array_key_exists('max', $rules));
            $input = $data[$type];

            foreach (array_keys($rules) as $rule) {
                //! Tutaj zwrócic wyjątek, że brakuje message do rules
                $message = $rules[$rule]['message'] ?? "Wyjątek";
                $value = $rules[$rule]['value'];

                // ================================================
                if (($rule == "min" || $rule == "max") && $between == true && self::$betweenStatus == false) {
                    $min = $rules['min']['value'];
                    $max = $rules['max']['value'];

                    if ($this->strlenBetween($input, $min - 1, $max + 1) == false) {
                        Session::set("error:$type:between", $rules['min']['message']);
                        $ok = false;
                    }

                    self::$betweenStatus = true;
                }
                // ================================================
                else if ($rule == "max" && $between == false) {
                    if ($this->strlenMax($input, $value) == false) {
                        Session::set("error:$type:$rule", $message);
                        $ok = false;
                    }
                }
                // ================================================
                else if ($rule == "min" && $between == false) {
                    if ($this->strlenMin($input, $value) == false) {
                        Session::set("error:$type:$rule", $message);
                        $ok = false;
                    }
                }
                // ================================================
                else if ($rule == "validate" && $value == true) {
                    if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
                        Session::set("error:$type:$rule", $message);
                        $ok = false;
                    }
                }
                // ================================================
                else if ($rule == "sanitize" && $value == true) {
                    if ($input != filter_var($input, FILTER_SANITIZE_EMAIL)) {
                        Session::set("error:$type:$rule", $message);
                        $ok = false;
                    }
                }
                // ================================================
            }
        }

        return $ok ?? true;
    }
}
