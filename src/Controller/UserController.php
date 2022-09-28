<?php

declare (strict_types = 1);

namespace App\Controller;

use Phantom\Controller\Controller;
use Phantom\Helper\Request;
use Phantom\RedirectToRoute;
use Phantom\View;

class UserController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->requireLogin();
    }

    public function logoutAction(): RedirectToRoute
    {
        $this->user->logout();
        return $this->redirect(self::$config->get('default.route.logout'), [
            'email' => $this->user->email,
        ]);
    }

    public function profileAction(): View
    {
        View::set(['title' => "Profil użytkownika", 'style' => "profile"]);
        return $this->render('user/profile');
    }

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

    private function updateUsername()
    {
        if ($username = $this->request->hasPostName('username')) {
            $this->user->set('username', $username);
            $this->user->updateUsername();
        }
    }

    private function updatePassword()
    {
        if ($data = $this->request->hasPostNames(['current_password', 'password', 'repeat_password'])) {
            $this->user->updatePassword($data);
        }
    }

    private function updateAvatar()
    {
        $path = self::$config->get('upload.path.avatar');

        if ($file = $this->request->file('avatar')) {
            $this->user->updateAvatar($file, $path);
        }
    }
}
