<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Helper\Session;
use App\Model\Mail;

class GeneralController extends AbstractController
{
    public $default_action = 'home';

    public function homeAction()
    {
        $this->view->render('general/home');
    }

    public function policyAction()
    {
        $this->view->render('general/policy');
    }

    public function regulationsAction()
    {
        $this->view->render('general/regulations');
    }

    public function contactAction()
    {
        $names = ['name', 'from', 'message', 'subject'];

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $data = $this->request->postParams($names);
            $mail = new Mail($data);

            if ($mail->send(self::$configuration['mail'])) {
                Session::set('success', "Wiadomość została wysłana");
            } else {
                Session::set('error', "Nie udało się wysłać wiadomości, prosimy spróbować później");
            }

            $this->redirect(self::$route['contact']);
        }

        // === === === === ===
        $social = [
            "facebook" => [
                'icon' => "https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Facebook_f_logo_%282019%29.svg/1200px-Facebook_f_logo_%282019%29.svg.png",
                'link' => "https://www.facebook.com/",
            ],
            "linkedin" => [
                'icon' => "https://play-lh.googleusercontent.com/kMofEFLjobZy_bCuaiDogzBcUT-dz3BBbOrIEjJ-hqOabjK8ieuevGe6wlTD15QzOqw",
                'link' => "https://www.linkedin.com/feed/",
            ],
        ];

        $this->view->render('general/contact', ['social' => $social ?? []], ['contact']);
    }
}
