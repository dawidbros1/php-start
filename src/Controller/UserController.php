<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Controller\Controller;
use App\Helper\Request;
use App\View;

class UserController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->requireLogin();
    }

    public function logoutAction(): void
    {
        $this->user->logout();
        $this->redirect(self::$config->get('default.route.logout'), ['email' => $this->user->email]);
    }

    public function profileAction(): void
    {
        View::set(['title' => "Profil użytkownika", 'style' => "profile"]);
        $this->view->render('user/profile');

    }

    public function updateAction(): void
    {
        if ($this->request->isPost()) {
            $toUpdate = $this->request->postParam('update');

            if (in_array($toUpdate, ['username', 'password', 'avatar'])) {
                $action = "update" . ucfirst($toUpdate);
                $this->$action();
            }
        }

        $this->redirect('user.profile');
    }

    private function updateUsername(): void
    {
        if ($this->request->hasPostName('username')) {
            $data = ['username' => $this->request->postParam('username')];
            $this->user->updateUsername($data);
        }
    }

    private function updatePassword(): void
    {
        $names = ['current_password', 'password', 'repeat_password'];

        if ($this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);
            $this->user->updatePassword($data);
        }
    }

    private function updateAvatar(): void
    {
        $path = self::$config->get('upload.path.avatar');
        $defaultAvatar = self::$config->get('default.path.avatar');

        if ($file = $this->request->file('avatar')) {
            $this->user->updateAvatar($file, $path, $defaultAvatar);
        }
    }
}
