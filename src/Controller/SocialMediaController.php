<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Controller\AbstractController;
use App\Helper\Request;
use App\Repository\SocialMediaRepository;

class SocialMediaController extends AbstractController
{
    public $default_action = 'list';

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->requireAdmin();
        $this->repository = new SocialMediaRepository();
    }

    public function listAction()
    {
        $media = $this->repository->getAll();
        $this->view->render('socialMedia/list', ['media' => $media], ['socialMedia']);
    }

    public function showAction()
    {
    }

    public function createAction()
    {
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}
