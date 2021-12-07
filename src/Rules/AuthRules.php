<?php

declare (strict_types = 1);

namespace App\Rules;

use App\Model\Rules;

class AuthRules extends Rules
{
    public function rules()
    {
        $this->createRules('username', ['min' => 6, "max" => 16]);
        $this->createRules('password', ['min' => 6, "max" => 16]);
    }

    public function messages()
    {
        $this->createMessages('username', [
            'strlen' => "Nazwa użytkownika powinna zawierać od " . $this->getValue('username', 'min') . " do " . $this->getValue('username', 'max') . " znaków",
        ]);

        $this->createMessages('password', [
            'strlen' => "Hasło powinno zawierać od " . $this->getValue('username', 'min') . " do " . $this->getValue('username', 'max') . " znaków",
            'same' => "Podane hasła nie są identyczne",
        ]);

        $this->createMessages('email', [
            'sanitize' => "Adres email zawiera niedozwolone znaki",
            'validate' => "Adres email nie jest prawidłowy",
        ]);
    }
}