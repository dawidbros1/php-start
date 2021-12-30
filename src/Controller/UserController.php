<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Controller\Controller;
use App\Helper\Request;
use App\Helper\Session;
use App\Repository\UserRepository;
use App\Rules\UserRules;
use App\View;

class UserController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->requireLogin();
        $this->rules = new UserRules();
    }

    public function logoutAction()
    {
        $this->user->logout();
        Session::set('success', "Nastąpiło wylogowanie z systemu");
        $this->redirect(self::$route->get('auth.login'), ['email' => $this->user->email]);
    }

    public function profileAction()
    {
        View::set(['title' => "Profil użytkownika", 'style' => "profile"]);
        $this->view->render('user/profile');

    }

    public function updateAction()
    {
        if ($this->request->isPost()) {
            $update = $this->request->postParam('update');

            switch ($update) {
                case 'username':{
                        $this->updateUsername();
                        break;
                    }
                case 'password':{
                        $this->updatePassword();
                        break;
                    }
                case 'avatar':{
                        $this->updateAvatar();
                        break;
                    }
            }
        }

        $this->redirect(self::$route->get('user.profile'));
    }

    private function updateUsername()
    {
        if ($this->request->hasPostName('username')) {
            $data = ['username' => $this->request->postParam('username')];

            if ($this->validate($data, $this->rules)) {
                $this->user->update($data);
                $this->userRepository->update($this->user, 'username');
                Session::set('success', "Nazwa użytkownika została zmieniona");
            }
        }
    }

    private function updatePassword()
    {
        $names = ['current_password', 'password', 'repeat_password'];

        if ($this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);

            if (!$same = ($this->user->password == $this->hash($data['current_password']))) {
                Session::set("error:password:current", "Podane hasło jest nieprawidłowe");
            }

            if ($this->validate($data, $this->rules) && $same) {
                $data['password'] = $this->hash($data['password']);
                $this->user->update($data);
                $this->userRepository->update($this->user, 'password');
                Session::set('success', 'Hasło zostało zaktualizowane');
            }
        }
    }

    private function updateAvatar()
    {
        $path = self::$config->get('upload.path.avatar');
        $defaultAvatar = self::$config->get('default.path.avatar');

        if ($file = $this->request->file('avatar')) {
            if ($this->validateImage($file, $this->rules, 'avatar')) {
                $file = $this->hashFile($file);

                if ($this->uploadFile($path, $file)) {
                    if ($this->user->avatar != $defaultAvatar) {
                        $this->user->deleteAvatar();
                    }

                    $this->user->update(['avatar' => $path . $file['name']]);
                    $this->userRepository->update($this->user, 'avatar');
                    Session::set('success', 'Awatar został zaktualizowany');
                }
            }
        }
    }
}
