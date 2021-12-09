<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Controller\AbstractController;
use App\Helper\Request;
use App\Helper\Session;

class UserController extends AbstractController
{
    public $default_action = 'profile';

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function logoutAction()
    {
        $this->user->logout();
        Session::set('success', "Nastąpiło wylogowanie z systemu");
        $this->redirect(self::$route['auth.login'], ['email' => $this->user ? $this->user->email : '']);
    }

    public function profileAction()
    {
        $this->view->render('user/profile', [], ['profile']);
    }

    public function updateUsernameAction()
    {
        if ($this->request->isPost() && $this->request->hasPostName('username')) {
            $username = $this->request->postParam('username');
            if ($this->user->updateUsername($username)) {
                Session::set('success', 'Nazwa użytkownika została zaktualizowana');
            }
        }

        $this->redirect(self::$route['user.profile']);
    }

    public function updatePasswordAction()
    {
        $names = ['current_password', 'password', 'repeat_password'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);
            if ($this->user->updatePassword($data)) {
                Session::set('success', 'Hasło zostało zaktualizowane');
            }
        }

        $this->redirect(self::$route['user.profile']);
    }
}
