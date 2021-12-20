<?php

declare (strict_types = 1);

namespace App\Validator;

use App\Helper\Session;

// use App\Excep

class Validator
{
    // Metody walidacyjne wielokrotnego użytku
    protected function strlenBetween(string $variable, int $min, int $max)
    {
        if (strlen($variable) > $min && strlen($variable) < $max) {
            return true;
        }

        return false;
    }

    protected function strlenMax(string $input, int $max)
    {
        if (strlen($input) > $max) {
            return false;
        }

        return true;
    }

    protected function strlenMin(string $input, int $min)
    {
        if (strlen($input) < $min) {
            return false;
        }

        return true;
    }

    // Ogólna klasa VALIDATORA

    public function validate(array $data, $rules)
    {
        $types = array_keys($data);

        if (array_key_exists('password', $data) && array_key_exists('repeat_password', $data)) {
            if ($data['password'] != $data['repeat_password']) {
                Session::set("error:password:same", "Hasła nie są jednakowe");
                $ok = false;
            }
        }

        foreach ($types as $type) {
            if (!$rules->hasType($type)) {continue;}

            $rules->selectType($type);
            $between = (bool) $rules->hasKeys(['min', 'max']);
            $input = $data[$type];

            foreach (array_keys($rules->get()) as $rule) {
                $value = $rules->value($rule);
                $message = $rules->message($rule);

                // ================================================
                if (($rule == "min" || $rule == "max") && $between) {
                    $min = $rules->value('min');
                    $max = $rules->value('max');

                    if ($this->strlenBetween($input, $min - 1, $max + 1) == false) {
                        Session::set("error:$type:between", $message);
                        $ok = false;
                    }

                    $between = false;
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
                else if ($rule == "require" && $value == true) {
                    if (empty($input)) {
                        Session::set("error:$type:$rule", $message);
                        $ok = false;
                    }
                }
                // ================================================
                else if ($rule == "specialCharacters" && $value == true) {
                    if (preg_match('/[\'^£$%&*()}{@#~"?><>,|=_+¬-]/', $input)) {
                        Session::set("error:$type:$rule", $message);
                        $ok = false;
                    }
                }
                // ================================================
            }
        }

        return $ok ?? true;
    }

    public function validateImage($FILE, $rules, $type)
    {
        $uploadOk = 1;

        if (empty($FILE['name'])) {
            Session::set('error:file:empty', "Nie został wybrany żaden plik");
            return 0;
        }

        $check = getimagesize($FILE["tmp_name"]);

        if ($check === false && $uploadOk) {
            Session::set('error:file:notImage', 'Przesłany plik nie jest obrazem');
            return 0;
        }

        $rules->selectType($type);

        if ($rules->hasKeys(['maxSize'])) {
            if (($FILE["size"] >= $rules->value('maxSize')) && $uploadOk) {
                Session::set('error:file:maxSize', $rules->message('maxSize'));
                $uploadOk = 0;
            }
        }

        if ($rules->hasKeys(['types'])) {
            $type = strtolower(pathinfo($FILE['name'], PATHINFO_EXTENSION));

            if (!in_array($type, $rules->value('types'))) {
                Session::set('error:file:types', $rules->message('types'));
                $uploadOk = 0;
            }
        }

        return $uploadOk;
    }
}
