<?php

declare (strict_types = 1);

namespace App\Rules;

use App\Rules\AuthRules;

class UserRules extends AuthRules
{
    public function __construct()
    {
        $this->rules();
        parent::rules();

        $this->messages();
        parent::messages();
    }

    public function rules()
    {
        $this->createRule('avatar', [
            'maxSize' => 512 * 512,
            'types' => ['jpg', 'png', 'jpeg', 'gif'],
        ]);
    }

    public function messages()
    {
        $this->createMessages('avatar', [
            'maxSize' => "Przesłany plik jest zbyt duży. Rozmiar pliku nie może być większy niż " . $this->value('avatar.maxSize') . " pixelów",
            'types' => "Przesyłany plik posiada niedozwolone rozszerzenie. Dozwolone rozszeszenia to: " . $this->arrayValue('avatar.types', true),
        ]);
    }
}
