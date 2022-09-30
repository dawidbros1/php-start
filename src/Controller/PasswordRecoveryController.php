<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Model\PasswordRecovery;
use App\Model\User;
use Phantom\Controller\Controller;
use Phantom\Helper\Request;
use Phantom\Helper\Session;
use Phantom\RedirectToRoute;
use Phantom\View;

class PasswordRecoveryController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->forGuest();
        $this->model = new PasswordRecovery([], true, "User");
    }

    public function forgotAction(): View | RedirectToRoute
    {
        View::set(['title' => "Przypomnienie hasła"]);

        if ($email = $this->request->isPost(['email'])) {
            if ($this->model->existsEmail($email)) {
                $username = $this->model->find(['email' => $email], "", false, User::class)->username;
                $this->mail->forgotPassword($email, self::$route->get('passwordRecovery.reset'), $username);
            } else {
                Session::set("error:email:null", "Podany adres email nie istnieje");
            }
            return $this->redirect('passwordRecovery.forgot');
        } else {
            return $this->render('passwordRecovery/forgot');
        }
    }

    public function resetAction(): View | RedirectToRoute
    {
        View::set(['title' => "Reset hasła"]);

        if ($data = $this->request->isPost(['password', 'repeat_password', 'code'])) {
            $this->checkCodeToResetPassword($code = $data['code']);

            if ($user = $this->model->resetPassword($data, $code)) {
                return $this->redirect('authorization', ['email' => $user->email]);
            } else {
                return $this->redirect('passwordRecovery.reset', ['code' => $code]);
            }
        }

        if ($code = $this->request->isGet(['code'])) {
            $this->checkCodeToResetPassword($code);
            return $this->render('passwordRecovery/reset', ['email' => Session::get($code), 'code' => $code]);
        } else {
            Session::set('error', 'Kod resetu hasła nie został podany');
            return $this->redirect('passwordRecovery.forgot');
        }
    }

    private function checkCodeToResetPassword($code)
    {
        $names = [$code, "created:" . $code];

        if (Session::hasArray($names)) {
            if ((time() - Session::get("created:" . $code)) > 86400) {
                Session::set('error', 'Link do zresetowania hasła stracił ważność');
                Session::clearArray($names);
                return $this->redirect('passwordRecovery.forgot');
            }
        } else {
            Session::set('error', 'Nieprawidłowy kod resetu hasła');
            return $this->redirect('passwordRecovery.forgot');
        }
    }
}
