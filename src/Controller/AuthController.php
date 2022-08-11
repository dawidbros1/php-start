<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Controller\Controller;
use App\Helper\Checkbox;
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

        if ($data = $this->request->isPost(['username', 'email', 'password', 'repeat_password'])) {
            $data['avatar'] = self::$config->get('default.path.avatar');
            $data['regulations'] = Checkbox::get($this->request->postParam('regulations', false));

            if ($this->model->register($data)) {
                $this->redirect('auth.login', ['email' => $data['email']]);
            } else {
                unset($data['password'], $data['repeat_password']);
                $this->redirect('auth.register', $data);
            }
        } else {
            $this->view->render('auth/register', $this->request->getParams(['username', 'email']));
        }
    }

    public function loginAction(): void
    {
        View::set(['title' => "Logowanie"]);

        if ($data = $this->request->isPost(['email', 'password'])) {
            if ($this->model->login($data)) {
                $this->redirect(self::$config->get('default.route.home'));
            } else {
                if ($this->model->existsEmail($data['email'])) {
                    Session::set("error:password:incorrect", "Wprowadzone hasło jest nieprawidłowe");
                } else {
                    Session::set("error:email:null", "Podany adres email nie istnieje");
                }
                unset($data['password']);
                $this->redirect('auth.login', $data);
            }

        } else {
            $this->view->render('auth/login', ['email' => $this->request->getParam('email')]);
        }
    }

    public function forgotPasswordAction(): void
    {
        View::set(['title' => "Przypomnienie hasła"]);
        if ($email = $this->request->isPost(['email'])) {
            if ($this->model->existsEmail($email)) {
                $username = $this->model->find(['email' => $email])->username;
                $this->mail->forgotPassword($email, self::$route->get('auth.resetPassword'), $username);
            } else {
                Session::set("error:email:null", "Podany adres email nie istnieje");
            }
            $this->redirect('auth.forgotPassword');
        } else {
            $this->view->render('auth/forgotPassword');
        }
    }

    public function resetPasswordAction(): void
    {
        View::set(['title' => "Reset hasła"]);

        if ($data = $this->request->isPost(['password', 'repeat_password', 'code'])) {
            $this->checkCodeToResetPassword($code = $data['code']);

            if ($user = $this->model->resetPassword($data, $code)) {
                $this->redirect('auth.login', ['email' => $user->email]);
            } else {
                $this->redirect('auth.resetPassword', ['code' => $code]);
            }
        }

        if ($code = $this->request->isGet(['code'])) {
            $this->checkCodeToResetPassword($code);
            $this->view->render('auth/resetPassword', ['email' => Session::get($code), 'code' => $code]);
        } else {
            Session::set('error', 'Kod resetu hasła nie został podany');
            $this->redirect('auth.forgotPassword');
        }
    }

    private function checkCodeToResetPassword($code): void
    {
        $names = [$code, "created:" . $code];

        if (Session::hasArray($names)) {
            if ((time() - Session::get("created:" . $code)) > 86400) {
                Session::set('error', 'Link do zresetowania hasła stracił ważność');
                Session::clearArray($names);
                $this->redirect('auth.forgotPassword');
            }
        } else {
            Session::set('error', 'Nieprawidłowy kod resetu hasła');
            $this->redirect('auth.forgotPassword');
        }
    }
}
