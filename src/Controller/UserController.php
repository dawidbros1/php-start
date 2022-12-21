<?php

declare (strict_types = 1);

namespace App\Controller;

use Phantom\Controller\AbstractController;
use Phantom\Helper\Session;
use Phantom\RedirectToRoute;
use Phantom\Request\Request;
use Phantom\View;

class UserController extends AbstractController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->forLogged();
    }

    # Method logouts user
    public function logoutAction(): RedirectToRoute
    {
        Session::clear('user:id');
        Session::success("Nastąpiło wylogowanie z systemu");

        return $this->redirect(self::$config->get('default.route.logout'), [
            'email' => $this->user->email,
        ]);
    }

    # Method shows user profile
    public function profileAction(): View
    {
        View::set("Profil użytkownika", "profile");
        return $this->render('user/profile');
    }

    # Method runs method to update [ username | password | avatar ]
    public function updateAction(): RedirectToRoute
    {
        if ($toUpdate = $this->request->isPost(['update'])) {
            if (in_array($toUpdate, ['username', 'password', 'avatar'])) {
                $action = "update" . ucfirst($toUpdate);
                $this->$action();
            }
        }

        return $this->redirect('user.profile');
    }

    # Method updates username
    private function updateUsername()
    {
        if ($username = $this->request->hasPostName('username')) {
            $this->user->updateUsername($username);
        }
    }

    # Method updates password
    private function updatePassword()
    {
        if ($data = $this->request->hasPostNames(['current_password', 'password', 'repeat_password'])) {
            $this->user->updatePassword($data);
        }
    }

    # Method updates avatar
    private function updateAvatar()
    {
        $path = self::$config->get('upload.path.avatar');

        if ($file = $this->request->file('avatar')) {
            $this->user->updateAvatar($file, $path);
        }
    }
}
