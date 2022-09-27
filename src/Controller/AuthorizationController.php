<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Model\Authorization;
use Phantom\Controller\Controller;
use Phantom\Helper\Request;
use Phantom\Helper\Session;
use Phantom\Model\Mail;
use Phantom\View;

class AuthorizationController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->guest();
        $this->model = new Authorization();
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
                $this->redirect('authorization.login', $data);
            }

        } else {
            $this->view->render('authorization/login', ['email' => $this->request->getParam('email')]);
        }
    }

    public function forgotPasswordAction(): void
    {
        View::set(['title' => "Przypomnienie hasła"]);
        if ($email = $this->request->isPost(['email'])) {
            if ($this->model->existsEmail($email)) {
                $username = $this->model->find(['email' => $email])->username;
                $this->mail->forgotPassword($email, self::$route->get('authorization.resetPassword'), $username);
            } else {
                Session::set("error:email:null", "Podany adres email nie istnieje");
            }
            $this->redirect('authorization.forgotPassword');
        } else {
            $this->view->render('authorization/forgotPassword');
        }
    }

    public function resetPasswordAction(): void
    {
        View::set(['title' => "Reset hasła"]);

        if ($data = $this->request->isPost(['password', 'repeat_password', 'code'])) {
            $this->checkCodeToResetPassword($code = $data['code']);

            if ($user = $this->model->resetPassword($data, $code)) {
                $this->redirect('authorization.login', ['email' => $user->email]);
            } else {
                $this->redirect('authorization.resetPassword', ['code' => $code]);
            }
        }

        if ($code = $this->request->isGet(['code'])) {
            $this->checkCodeToResetPassword($code);
            $this->view->render('authorization/resetPassword', ['email' => Session::get($code), 'code' => $code]);
        } else {
            Session::set('error', 'Kod resetu hasła nie został podany');
            $this->redirect('authorization.forgotPassword');
        }
    }

    private function checkCodeToResetPassword($code): void
    {
        $names = [$code, "created:" . $code];

        if (Session::hasArray($names)) {
            if ((time() - Session::get("created:" . $code)) > 86400) {
                Session::set('error', 'Link do zresetowania hasła stracił ważność');
                Session::clearArray($names);
                $this->redirect('authorization.forgotPassword');
            }
        } else {
            Session::set('error', 'Nieprawidłowy kod resetu hasła');
            $this->redirect('authorization.forgotPassword');
        }
    }
}
