<?php

declare (strict_types = 1);

namespace App\Model;

use Phantom\Helper\Session;
use Phantom\Model\Model;

class PasswordRecovery extends Model
{
    public function resetPassword($data, $code)
    {
        if ($status = $this->validate($data)) {
            $user = $this->find(['email' => Session::get($code)], "", true, User::class);
            $user->set('password', $this->hash($data['password']));
            $user->update([], false);

            Session::clearArray([$code, "created:" . $code]);
            Session::success('Hasło do konta zostało zmienione');
        }
        return $user ?? null;
    }

    public function existsEmail($email)
    {
        return in_array($email, $this->repository->getEmails());
    }
}
