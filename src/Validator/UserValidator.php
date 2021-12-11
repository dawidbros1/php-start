<?php

declare (strict_types = 1);

namespace App\Validator;

use App\Helper\Session;
use App\Validator\AbstractValidator;

abstract class UserValidator extends AbstractValidator
{
    protected function validateAvatarFile($FILE)
    {
        $check = getimagesize($FILE["tmp_name"]);

        if ($check === false) {
            Session::set('error', 'Przesłany plik nie jest obrazem');
            $uploadOk = 0;
        }

        // Check file size
        if (($FILE["size"] > 500000) && $uploadOk) {
            Session::set('error', 'Przesłany plik jest zbyt duży');
            $uploadOk = 0;
        }

        $type = strtolower(pathinfo($FILE['name'], PATHINFO_EXTENSION));

        // Allow certain file formats
        if ($type != "jpg" && $type != "png" && $type != "jpeg" && $type != "gif" && $uploadOk) {
            Session::set('error', 'Przesyłany plik posiada niedozwolone rozszerzenie. Dozwolone rozszeszenia to: JPG, JPEG, PNG oraz GIF');
            $uploadOk = 0;
        }

        return $uploadOk ?? true;
    }
}
