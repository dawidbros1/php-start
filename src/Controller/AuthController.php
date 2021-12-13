<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Helper\Request;
use App\Helper\Session;
use App\Model\Auth;

class AuthController extends AbstractController
{
    public $default_action = 'register';
    private $auth;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->auth = new Auth();
    }

    public function registerAction(): void
    {
        $names = ['username', 'email', 'password', 'repeat_password'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);
            $data['defaultPathAvatar'] = self::$configuration['default']['path']['avatar'];
            if ($this->auth->register($data, self::$configuration['hash']['method'])) {
                Session::set('success', 'Konto zostało utworzone');
                $this->redirect(self::$route['auth.login'], ['email' => $data['email']]);
            } else {
                $this->view->render('auth/register', ['data' => $data]);
            }
        } else {
            $this->view->render('auth/register');
        }
    }

    public function loginAction(): void
    {
        $names = ['email', 'password'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);

            if ($user = $this->auth->login($data['email'], $data['password'], self::$configuration['hash']['method'])) {
                Session::set('user:id', $user['id']);
                $lastPage = Session::getNextClear('lastPage');
                $this->redirect($lastPage ? "?" . $lastPage : self::$route['home']);
            } else {
                $this->view->render('auth/login', ['email' => $data['email']]);
            }
        } else {
            $this->view->render('auth/login', ['email' => $this->request->getParam('email')]);
        }
    }
}
