<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Model\Auth;
use App\Rules\UserRules;
use Phantom\Controller\AbstractController;
use Phantom\Helper\CheckBox;
use Phantom\Helper\Session;
use Phantom\Request\Request;
use Phantom\Validator\Validator;
use Phantom\View;

class AuthController extends AbstractController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->forGuest();
    }

    public function registerAction()
    {
        View::set("Rejestracja");

        // if ($status = ($user->validate($data)&($this->isEmailUnique($user)))) {

        if ($data = $this->request->isPost(['username', 'email', 'password', 'repeat_password'])) {
            $data['regulations'] = CheckBox::get($this->request->postParam('regulations', false));

            $validator = new Validator($data, new UserRules());

            if ($validator->validate() && $validator->validatePassword()) {
                (new Auth($data))->create();
                return $this->redirect('auth.login', ['email' => $data['email']]);
            } else {
                unset($data['password'], $data['repeat_password']);
                return $this->redirect('auth.register', $data);
            }

        } else {
            return $this->render('auth/registration',
                $this->request->getParams(['username', 'email'])
            );
        }
    }

    public function loginAction()
    {
        View::set("Logowanie");

        if ($data = $this->request->isPost(['email', 'password'])) {
            if ((new Auth())->login($data)) {
                return $this->redirect(self::$config->get('default.route.home'));
            } else {
                if ($this->model->existsEmail($data['email'])) {
                    Session::set("error:password:incorrect", "Wprowadzone hasło jest nieprawidłowe");
                } else {
                    Session::set("error:email:null", "Podany adres email nie istnieje");
                }
                unset($data['password']);
                return $this->redirect('auth.login', $data);
            }
        } else {
            return $this->render('auth/login', [
                'email' => $this->request->getParam('email'),
            ]);
        }
    }
}
