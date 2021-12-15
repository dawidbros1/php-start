<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Controller\AbstractController;
use App\Helper\Request;
use App\Helper\Session;
use App\Model\SocialMedia;

class SocialMediaController extends AbstractController
{
    public $default_action = 'list';

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->requireAdmin();
        $this->model = new SocialMedia();
    }

    public function listAction()
    {
        $data = $this->request->getParams(['name', 'link']);
        $media = $this->model->getAll();
        $this->view->render('socialMedia/list', ['media' => $media, "data" => $data], ['socialMedia']);
    }

    public function showAction()
    {
    }

    public function createAction()
    {
        $names = ['name', 'link'];
        $file = $this->request->file('icon') ?? null;

        if ($this->request->hasPostNames($names) && $file) {
            $data = $this->request->postParams($names);
            $path = self::$configuration['upload']['path']['social_media'];

            if ($this->model->save($file, $path, $data)) {
                Session::set('success', 'Medium społecznościowe zostało dodane');
            }
        }

        $this->redirect(self::$route['social.list'], $data ?? []);

    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}
