<?php

declare(strict_types=1);

namespace App\Model;

use App\Rules\UserRules;
use Phantom\Helper\Session;
use Phantom\Repository\DBFinder;
use Phantom\Validator\Validator;

class PasswordRecovery
{
    protected $table = "users";

    # Method sets new password for user
    public function resetPassword($data, $code, $hashMethod)
    {
        $validator = new Validator($data, new UserRules());

        if ($validator->validate()) {
            $user = DBFinder::getInstance($this->table)->find(['email' => Session::get($code)], User::class);
            $user->setPassword(hash($hashMethod, $data['password']));
            $user->update('password');
            Session::clearArray([$code, "created:" . $code]);
            Session::success('Hasło do konta zostało zmienione');
        }
        return $user ?? null;
    }
}