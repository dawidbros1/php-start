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
        View::setStyle("contact");
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

        $path = self::$configuration['default']['path']['medium'] ?? "";
        $this->view->render('general/contact', ['path' => $path]);
    }
}
