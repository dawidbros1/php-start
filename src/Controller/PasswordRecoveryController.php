<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Model\PasswordRecovery;
use App\Model\User;
use Phantom\Controller\Controller;
use Phantom\Helper\Request;
use Phantom\Helper\Session;
use Phantom\View;

class PasswordRecoveryController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->guest();
        $this->model = new PasswordRecovery([], true, "User");
    }

    public function forgotAction(): void
    {
        View::set(['title' => "Przypomnienie hasła"]);

        if ($email = $this->request->isPost(['email'])) {
            if ($this->model->existsEmail($email)) {
                $username = $this->model->find(['email' => $email], "", false, User::class)->username;
                $this->mail->forgotPassword($email, self::$route->get('passwordRecovery.reset'), $username);
            } else {
                Session::set("error:email:null", "Podany adres email nie istnieje");
            }
            $this->redirect('passwordRecovery.forgot');
        } else {
            $this->view->render('passwordRecovery/forgot');
        }
    }

    public function resetAction(): void
    {
        View::set(['title' => "Reset hasła"]);

        if ($data = $this->request->isPost(['password', 'repeat_password', 'code'])) {
            $this->checkCodeToResetPassword($code = $data['code']);

            if ($user = $this->model->resetPassword($data, $code)) {
                $this->redirect('authorization', ['email' => $user->email]);
            } else {
                $this->redirect('passwordRecovery.reset', ['code' => $code]);
            }
        }

        if ($code = $this->request->isGet(['code'])) {
            $this->checkCodeToResetPassword($code);
            $this->view->render('passwordRecovery/reset', ['email' => Session::get($code), 'code' => $code]);
        } else {
            Session::set('error', 'Kod resetu hasła nie został podany');
            $this->redirect('passwordRecovery.forgot');
        }
    }

    private function checkCodeToResetPassword($code): void
    {
        $names = [$code, "created:" . $code];

        if (Session::hasArray($names)) {
            if ((time() - Session::get("created:" . $code)) > 86400) {
                Session::set('error', 'Link do zresetowania hasła stracił ważność');
                Session::clearArray($names);
                $this->redirect('passwordRecovery.forgot');
            }
        } else {
            Session::set('error', 'Nieprawidłowy kod resetu hasła');
            $this->redirect('passwordRecovery.forgot');
        }
    }
}
