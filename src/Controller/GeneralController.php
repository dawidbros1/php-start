<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Helper\Session;
use App\Model\Mail;
use App\View;

class GeneralController extends Controller
{
    public function homeAction(): void
    {
        View::set(['title' => "Strona główna", "style" => "home"]);
        $this->view->render('general/home');
    }

    public function policyAction(): void
    {
        View::set(['title' => "Polityka prywatności"]);
        $this->view->render('general/policy');
    }

    public function regulationsAction(): void
    {
        View::set(['title' => "Regulamin"]);
        $this->view->render('general/regulations');
    }

    public function contactAction(): void
    {
        View::set(['title' => "Strona kontaktowa", 'style' => "contact"]);
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
                $this->mail->contact($data);
            } else {
                Session::set('error:reCAPTCHA:robot', "Robotów nie wpuszczamy");
            }

            $this->redirect('contact');
        }

        $path = self::$config->get('default.path.medium') ?? "";
        $this->view->render('general/contact', ['path' => $path, 'sideKey' => self::$config->get('reCAPTCHA.key.side')]);
    }
}
