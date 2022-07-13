<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Controller\Controller;
use App\Helper\Request;
use App\Helper\Session;
use App\Model\Auth;
use App\Model\Mail;
use App\View;

class AuthController extends Controller
{
    private $auth;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->guest();
        $this->model = new Auth();
    }

    public function registerAction(): void
    {
        View::set(['title' => "Rejestracja"]);
        $names = ['username', 'email', 'password', 'repeat_password'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);
            $data['avatar'] = self::$config->get('default.path.avatar');

            if ($this->model->register($data)) {
                $this->redirect(self::$route->get('auth.login'), ['email' => $data['email']]);
            } else {
                unset($data['password'], $data['repeat_password']);
                $this->redirect(self::$route->get('auth.register'), $data);
            }
        } else {
            $this->view->render('auth/register', $this->request->getParams(['username', 'email']));
        }
    }

    public function loginAction(): void
    {
        View::set(['title' => "Logowanie"]);
        $names = ['email', 'password'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);

            if ($this->model->login($data)) {
                $this->redirect($lastPage ? "?" . $lastPage : self::$route->get('home'));
            } else {
                if ($this->model->existsEmail($data['email'])) {
                    Session::set("error:password:incorrect", "Wprowadzone hasło jest nieprawidłowe");
                } else {
                    Session::set("error:email:null", "Podany adres email nie istnieje");
                }
                unset($data['password']);
                $this->redirect(self::$route->get('auth.login'), $data);
            }

        } else {
            $this->view->render('auth/login', ['email' => $this->request->getParam('email')]);
        }
    }

    public function forgotPasswordAction(): void
    {
        View::set(['title' => "Przypomnienie hasła"]);
        if ($this->request->isPost() && $email = $this->request->postParam('email')) {
            if ($this->model->existsEmail($email)) {
                $username = $this->model->find(['email' => $email])->username;
                $this->mail->forgotPassword($email, self::$route->get('auth.resetPassword'), $username);
            } else {
                Session::set("error:email:null", "Podany adres email nie istnieje");
            }
            $this->redirect(self::$route->get('auth.forgotPassword'));
        } else {
            $this->view->render('auth/forgotPassword');
        }
    }

    public function resetPasswordAction(): void
    {
        View::set(['title' => "Reset hasła"]);
        $names = ['password', 'repeat_password', 'code'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);
            $code = $data['code'];
            $this->checkCodeToResetPassword($code);

            if ($user = $this->model->resetPassword($data, $code)) {
                $this->redirect(self::$route->get('auth.login'), ['email' => $user->email]);
            } else {
                $this->redirect(self::$route->get('auth.resetPassword'), ['code' => $code]);
            }
        }

        if ($this->request->isGet() && $code = $this->request->getParam('code')) {
            $this->checkCodeToResetPassword($code);
            $this->view->render('auth/resetPassword', ['email' => Session::get($code), 'code' => $code]);
        } else {
            Session::set('error', 'Kod resetu hasła nie został podany');
            $this->redirect(self::$route->get('auth.forgotPassword'));
        }
    }

    private function checkCodeToResetPassword($code): void
    {
        $names = [$code, "created:" . $code];

        if (Session::hasArray($names)) {
            if ((time() - Session::get("created:" . $code)) > 86400) {
                Session::set('error', 'Link do zresetowania hasła stracił ważność');
                Session::clearArray($names);
                $this->redirect(self::$route->get('auth.forgotPassword'));
            }
        } else {
            Session::set('error', 'Nieprawidłowy kod resetu hasła');
            $this->redirect(self::$route->get('auth.forgotPassword'));
        }
    }
}
