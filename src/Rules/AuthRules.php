<?php

declare (strict_types = 1);

namespace App\Rules;

use App\Model\Rules;

class AuthRules extends Rules
{
    public function rules()
    {
        // (TYPE, [name => value, name => value])
        $this->createRule('username', ['between' => ['min' => 3, "max" => 16], 'specialCharacters' => false]);
        $this->createRule('password', ['between' => ['min' => 6, 'max' => 36]]);
        $this->createRule('email', ['sanitize' => true, "validate" => true]);
    }

    public function messages()
    {
        $this->createMessages('username', [
            'between' => "Nazwa użytkownika powinna zawierać od " . $this->between('username.min') . " do " . $this->between('username.max') . " znaków",
            'specialCharacters' => "Nazwa użytkownika nie może zawierać znaków specjalnych",
        ]);

        $this->createMessages('password', [
            'between' => "Hasło powinno zawierać od " . $this->between('password.min') . " do " . $this->between('password.max') . " znaków",
        ]);

        $this->createMessages('email', [
            'sanitize' => "Adres email zawiera niedozwolone znaki",
            'validate' => "Adres email nie jest prawidłowy",
        ]);
    }
}
