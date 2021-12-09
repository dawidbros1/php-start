<?php

declare (strict_types = 1);

namespace App\Rules;

use App\Model\Rules;

class AuthRules extends Rules
{
    public function rules()
    {
        $this->createRules('username', ['min' => 3, "max" => 16]);
        $this->createRules('password', ['min' => 6, 'max' => 36]);
        $this->createRules('email', ['sanitize' => true, "validate" => true]);
    }

    public function messages()
    {
        // Exactly the same order as in the rules
        $this->createMessages('username', [
            'between' => "Nazwa użytkownika powinna zawierać od " . $this->getValue('min') . " do " . $this->getValue('max') . " znaków",
        ]);

        $this->createMessages('password', [
            'between' => "Hasło powinno zawierać od " . $this->getValue('min') . " do " . $this->getValue('max') . " znaków",
        ]);

        $this->createMessages('email', [
            'sanitize' => "Adres email zawiera niedozwolone znaki",
            'validate' => "Adres email nie jest prawidłowy",
        ]);
    }
}
