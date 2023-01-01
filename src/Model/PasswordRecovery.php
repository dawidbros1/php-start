<?php

declare (strict_types = 1);

namespace App\Model;

use App\Rules\UserRules;
use Phantom\Helper\Session;
use Phantom\Model\AbstractModel;
use Phantom\Validator\Validator;

class PasswordRecovery extends AbstractModel
{
    protected $table = "users";

    # Method sets new password for user
    public function resetPassword($data, $code)
    {
        $validator = new Validator($data, new UserRules());

        if ($validator->validate()) {
            $user = $this->find(['email' => Session::get($code)], User::class);
            $user->setPassword($this->hash($data['password']));
            $user->update('password');
            Session::clearArray([$code, "created:" . $code]);
            Session::success('Hasło do konta zostało zmienione');
        }
        return $user ?? null;
    }
}
