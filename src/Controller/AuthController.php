<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Controller\Controller;
use App\Helper\Request;
use App\Helper\Session;
use App\Model\Auth;
use App\Model\Mail;
use App\Model\User;
use App\Repository\AuthRepository;
use App\Rules\AuthRules;
use App\View;

class AuthController extends Controller
{
    private $auth;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->guest();
        $this->repository = new AuthRepository();
        $this->rules = new AuthRules();
    }

    public function registerAction(): void
    {
        View::set(['title' => "Rejestracja"]);
        $names = ['username', 'email', 'password', 'repeat_password'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);
            $emails = $this->repository->getEmails();

            if ($this->validate($data, $this->rules) && !Auth::isBusyEmail($data['email'], $emails)) {
                $data['password'] = $this->hash($data['password']);
                $data['avatar'] = self::$config->get('default.path.avatar');
                $user = new User($data);
                $user->escape();

                $this->repository->register($user);
                Session::set('success', 'Konto zostało utworzone');
                $this->redirect(self::$route->get('auth.login'), ['email' => $user->email]);
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

            if ($id = $this->repository->login($data['email'], $this->hash($data['password']))) {
                Session::set('user:id', $id);
                $lastPage = Session::getNextClear('lastPage');
                $this->redirect($lastPage ? "?" . $lastPage : self::$route->get('home'));
            } else {
                if (in_array($data["email"], $this->repository->getEmails())) {
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

    public function forgotPasswordAction()
    {
        View::set(['title' => "Przypomnienie hasła"]);
        if ($this->request->isPost() && $email = $this->request->postParam('email')) {
            if (in_array($email, $this->repository->getEmails())) {
                $location = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
                $code = rand(1, 1000000) . "_" . date('Y-m-d H:i:s');
                $hash = $this->hash($code, 'md5');

                Session::set($hash, $email);
                Session::set('created:' . $hash, time());

                $data = [];
                $data['email'] = $email;
                $data['link'] = $location . self::$route->get('auth.resetPassword') . "&code=$hash";
                $data['subject'] = $_SERVER['HTTP_HOST'] . " - Reset hasła";
                $data['username'] = $this->userRepository->get($email, 'email')->username;

                if (Mail::forgotPassword($data)) {
                    Session::set('success', "Link do zresetowania hasła został wysłany na podany adres email");
                }
            } else {
                Session::set("error:email:null", "Podany adres email nie istnieje");
            }

            $this->redirect(self::$route->get('auth.forgotPassword'));

        } else {
            $this->view->render('auth/forgotPassword');
        }
    }

    public function resetPasswordAction()
    {
        View::set(['title' => "Reset hasła"]);
        $names = ['password', 'repeat_password', 'code'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);
            $code = $data['code'];
            $this->checkCodeSession($data['code']);

            if ($this->validate($data, $this->rules)) {
                $user = $this->userRepository->get(Session::get($code), 'email');
                $user->password = $this->hash($data['password']);
                $this->userRepository->update($user, 'password');
                Session::clearArray([$code, "created:" . $code]);
                Session::set('success', 'Hasło do konta zostało zmienione');
                $this->redirect(self::$route->get('auth.login'), ['email' => $user->email]);
            } else {
                $this->redirect(self::$route->get('auth.resetPassword'), ['code' => $code]);
            }
        }

        if ($this->request->isGet() && $code = $this->request->getParam('code')) {
            $this->checkCodeSession($code);
            $this->view->render('auth/resetPassword', ['email' => Session::get($code), 'code' => $code]);
        } else {
            Session::set('error', 'Kod resetu hasła nie został podany');
            $this->redirect(self::$route->get('auth.forgotPassword'));
        }
    }

    private function checkCodeSession($code)
    {
        $names = [$code, "created:" . $code];

        if (Session::hasArray($names)) {
            if ((time() - Session::get("created:" . $code)) > 86400) {
                Session::set('error', 'Link do zresetowania hasła stracił ważność');
                Session::clearArray($names);
                $this->redirect(self::$route->get('auth.forgotPassword'));
            }
        } else {
            Session::set('error', 'Nieprawiłowy kod resetu hasła');
            $this->redirect(self::$route->get('auth.forgotPassword'));
        }
    }
}
