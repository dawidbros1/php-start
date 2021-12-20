<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Controller\Controller;
use App\Helper\Request;
use App\Helper\Session;
use App\Model\Auth;
use App\Model\User;
use App\Repository\AuthRepository;
use App\Rules\AuthRules;

class AuthController extends Controller
{
    private $auth;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->repository = new AuthRepository();
        $this->resourcebundle_locales = new AuthRules();
    }

    public function registerAction(): void
    {
        $names = ['username', 'email', 'password', 'repeat_password'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);
            $emails = $this->repository->getEmails();

            if ($this->validate($data, $this->resourcebundle_locales) && !Auth::isBusyEmail($data['email'], $emails)) {
                $data['password'] = $this->hash($data['password']);
                $data['avatar'] = self::$configuration['default']['path']['avatar'];
                $user = new User($data);
                $user->escape();

                $this->repository->register($user);
                Session::set('success', 'Konto zostało utworzone');
                $this->redirect(self::$route['auth.login'], ['email' => $user->email]);
            } else {
                unset($data['password'], $data['repeat_password']);
                $this->redirect(self::$route['auth.register'], $data);
            }
        } else {
            $this->view->render('auth/register', $this->request->getParams(['username', 'email']));
        }
    }

    public function loginAction(): void
    {
        $names = ['email', 'password'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);

            if ($id = $this->repository->login($data['email'], $this->hash($data['password']))) {
                Session::set('user:id', $id);
                $lastPage = Session::getNextClear('lastPage');
                $this->redirect($lastPage ? "?" . $lastPage : self::$route['home']);
            } else {
                if (in_array($data["email"], $this->repository->getEmails())) {
                    Session::set("error:password:incorrect", "Wprowadzone hasło jest nieprawidłowe");
                } else {
                    Session::set("error:email:null", "Podany adres email nie istnieje");
                }

                unset($data['password']);
                $this->redirect(self::$route['auth.login'], $data);
            }
        } else {
            $this->view->render('auth/login', ['email' => $this->request->getParam('email')]);
        }
    }
}
