<?php

declare(strict_types=1);

namespace App\Model;

use App\Base\BaseModel;
use Phantom\Helper\Session;

class Authorization extends BaseModel
{
    public function __construct()
    {
        parent::__construct([], true, "User");
    }

    # Method sets session user:id if there is a user with matching data [e-mail, password]
    public function login(array $data)
    {
        $data['password'] = $this->hash($data['password']);

        if ($user = $this->find(['email' => $data['email'], 'password' => $data['password']], "", false, User::class)) {
            Session::set('user:id', $user->id);
        }

        return $user;
    }

    # Method checks if email exists and return status
    public function existsEmail($email)
    {
        return in_array($email, $this->repository->getEmails());
    }
}
