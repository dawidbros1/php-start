<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Model\Registration;
use Phantom\Controller\Controller;
use Phantom\Helper\Checkbox;
use Phantom\Helper\Request;
use Phantom\View;

class RegistrationController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->guest();
        $this->model = new Registration();
    }

    public function index(): void
    {
        View::set(['title' => "Rejestracja"]);

        if ($data = $this->request->isPost(['username', 'email', 'password', 'repeat_password'])) {
            $data['regulations'] = Checkbox::get($this->request->postParam('regulations', false));

            if ($this->model->register($data)) {
                $this->redirect('auth.login', ['email' => $data['email']]);
            } else {
                unset($data['password'], $data['repeat_password']);
                $this->redirect('registration', $data);
            }
        } else {
            $this->view->render('registration/registration', $this->request->getParams(['username', 'email']));
        }
    }
}
