<?php

declare (strict_types = 1);

namespace App\Controller;

use Phantom\Controller\Controller;
use Phantom\Helper\Session;
use Phantom\Model\Mail;
use Phantom\View;

class GeneralController extends Controller
{
    public function homeAction()
    {
        View::set(['title' => "Strona główna", "style" => "home"]);
        return $this->render('general/home');
    }

    public function policyAction()
    {
        View::set(['title' => "Polityka prywatności"]);
        return $this->render('general/policy');
    }

    public function regulationsAction()
    {
        View::set(['title' => "Regulamin"]);
        return $this->render('general/regulations');
    }

    public function contactAction()
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
        return $this->render('general/contact', [
            'path' => $path,
            'sideKey' => self::$config->get('reCAPTCHA.key.side'),
        ]);
    }
}
