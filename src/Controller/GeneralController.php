<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Helper\Session;
use App\Model\Mail;
use App\View;

class GeneralController extends Controller
{
    public function homeAction()
    {
        View::set(['title' => "Strona główna"]);
        $this->view->render('general/home');
    }

    public function policyAction()
    {
        View::set(['title' => "Polityka prywatności"]);
        $this->view->render('general/policy');
    }

    public function regulationsAction()
    {
        View::set(['title' => "Regulamin"]);
        $this->view->render('general/regulations');
    }

    public function contactAction()
    {
        View::set(['title' => "Rejestracja", 'style' => "contact"]);
        $names = ['name', 'from', 'message', 'subject', 'g-recaptcha-response'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $secret = self::$config->get('reCAPTCHA.key.secret');
            $response = null;
            $reCaptcha = new \ReCaptcha($secret);

            $data = $this->request->postParams($names);

            $response = $reCaptcha->verifyResponse(
                $_SERVER["REMOTE_ADDR"],
                $data['g-recaptcha-response']
            );

            if ($response != null && $response->success) {
                if (Mail::contact($data)) {
                    Session::set('success', "Wiadomość została wysłana");
                }
            } else {
                Session::set('error:reCAPTCHA:robot', "Robotów nie wpuszczamy");
            }

            // ===== //

            $this->redirect(self::$route->get('contact'));
        }

        $path = self::$config->get('default.path.medium') ?? "";
        $this->view->render('general/contact', ['path' => $path, 'sideKey' => self::$config->get('reCAPTCHA.key.side')]);
    }
}
