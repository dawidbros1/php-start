<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Model\Authorization;
use Phantom\Controller\Controller;
use Phantom\Helper\Request;
use Phantom\Helper\Session;
use Phantom\View;

class AuthorizationController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->guest();
        $this->model = new Authorization([], true, "User");
    }
    public function index(): void
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
                $this->redirect('authorization', $data);
            }
        } else {
            $this->view->render('authorization/login', ['email' => $this->request->getParam('email')]);
        }
    }
}
