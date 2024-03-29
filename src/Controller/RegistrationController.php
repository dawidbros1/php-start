<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Registration;
use App\Base\BaseController;
use Phantom\Helper\Checkbox;
use Phantom\Helper\Request;
use Phantom\View;

class RegistrationController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->forGuest();
        $this->model = new Registration();
    }

    # Method adds new user
    public function index()
    {
        View::set("Rejestracja");

        if ($data = $this->request->isPost(['username', 'email', 'password', 'repeat_password'])) {
            $data['regulations'] = Checkbox::get($this->request->postParam('regulations', false));

            if ($this->model->register($data)) {
                return $this->redirect('authorization', ['email' => $data['email']]);
            } else {
                unset($data['password'], $data['repeat_password']);
                return $this->redirect('registration', $data);
            }
        } else {
            return $this->render(
                'registration/registration',
                $this->request->getParams(['username', 'email'])
            );
        }
    }
}
