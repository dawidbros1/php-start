<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AuthRepository;
use App\Helper\Request;

class AuthController extends AbstractController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->repository = new AuthRepository(self::$configuration['db']);
    }

    public function registerAction(): void
    {
        $this->view->render('auth/register');
    }

    public function loginAction(): void
    {
        $this->view->render('auth/login');
    }
}