<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Helper\Request;
use App\Helper\Session;

class UserController extends AbstractController
{
    public $default_action = 'none';

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function logoutAction()
    {
        $this->user->logout();
        Session::set('success', "Nastąpiło wylogowanie z systemu");
        $this->redirect('login', ['email' => $this->user ? $this->user->email : '']);
    }

    public function noneAction()
    {
        $this->redirectToMainPage();
    }

}