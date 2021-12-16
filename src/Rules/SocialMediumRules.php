<?php

declare (strict_types = 1);

namespace App\Rules;

use App\Model\Rules;

class SocialMediumRules extends Rules
{
    public function rules()
    {
        $this->createRules('name', ["min" => 2, "max" => 36]);
        $this->createRules('link', ["require" => true, 'max' => 255]);

        $this->createRules('icon', [
            'maxSize' => 512 * 512,
            'types' => ['jpg', 'png', 'jpeg'],
        ]);

    }

    public function messages()
    {
        $this->createMessages('name', [
            'between' => "Nazwa medium powinna zawierać od " . $this->value('name.min') . " do " . $this->value('name.max') . " znaków",
        ]);

        $this->createMessages('icon', [
            'maxSize' => "Przesłany plik jest zbyt duży. Rozmiar pliku nie może być większy niż " . $this->value('icon.maxSize') . " pixelów",
            'types' => "Przesyłany plik posiada niedozwolone rozszerzenie. Dozwolone rozszeszenia to: " . $this->arrayValue('icon.types', true),
        ]);

        $this->createMessages('link', [
            'require' => "Pole link nie może być puste",
            'max' => "Link nie może zawierać więcej niż " . $this->value('link.max') . " znaków",
        ]);
    }
}
