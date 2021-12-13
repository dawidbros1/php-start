<?php

declare (strict_types = 1);

namespace App\Validator;

use App\Helper\Session;
use App\Rules\UserRules;
use App\Validator\AbstractValidator;

abstract class UserValidator extends AbstractValidator
{
    protected function validateAvatarFile($FILE)
    {
        $uploadOk = 1;
        $check = getimagesize($FILE["tmp_name"]);

        if ($check === false) {
            Session::set('error', 'Przesłany plik nie jest obrazem');
            $uploadOk = 0;
        }

        $rules = new UserRules();
        $rules->selectType('avatar');

        // Check file size
        if (($FILE["size"] >= $rules->value('maxSize')) && $uploadOk) {
            Session::set('error', $rules->message('maxSize'));
            $uploadOk = 0;
        }

        $type = strtolower(pathinfo($FILE['name'], PATHINFO_EXTENSION));

        if ($uploadOk) {
            if (!in_array($type, $rules->value('types'))) {
                Session::set('error', $rules->message('types'));
                $uploadOk = 0;
            }
        }

        return $uploadOk;
    }
}
