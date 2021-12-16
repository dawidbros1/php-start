<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Helper\Request;
use App\Helper\Session;
use App\Model\Auth;
use App\Repository\AuthRepository;
use App\Rules\AuthRules;

class AuthController extends AbstractController
{
    private $auth;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->repository = new AuthRepository;
    }

    public function registerAction(): void
    {
        $names = ['username', 'email', 'password', 'repeat_password'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);
            $data['avatar'] = self::$configuration['default']['path']['avatar'];
            $rules = new AuthRules($data);

            $auth = new Auth($data);

            if ($auth->validate($data, $rules) && !$auth->isBusyEmail($this->repository->getEmails())) {
                $method = self::$configuration['hash']['method'];
                $auth->hashPassword($method);
                $this->repository->createAccount($auth);
                Session::set('success', 'Konto zostało utworzone');
                $this->redirect(self::$route['auth.login'], ['email' => $auth->email]);
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
            $auth = new Auth($data);
            $method = self::$configuration['hash']['method'];
            $auth->hashPassword($method);

            if ($user = $this->repository->login($auth)) {
                Session::set('user:id', $user['id']);
                $lastPage = Session::getNextClear('lastPage');
                $this->redirect($lastPage ? "?" . $lastPage : self::$route['home']);
            } else {
                $this->view->render('auth/login', ['email' => $data['email']]);
                if (in_array($auth->email, $this->repository->getEmails())) {
                    Session::set("error:password:incorrect", "Wprowadzone hasło jest nieprawidłowe");
                } else {
                    Session::set("error:email:null", "Podany adres email nie istnieje");
                }
            }

        } else {
            $this->view->render('auth/login', ['email' => $this->request->getParam('email')]);
        }
    }
}
