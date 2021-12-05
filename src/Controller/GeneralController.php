<?php

declare (strict_types = 1);

namespace App\Controller;

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
        $this->view->render('general/contact');
    }
}