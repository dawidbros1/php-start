<?php

declare (strict_types = 1);

namespace Phantom\Controller;

use App\Model\User;
use Phantom\Exception\ConfigurationException;
use Phantom\Exception\StorageException;
use Phantom\Helper\Request;
use Phantom\Helper\Session;
use Phantom\Model\Config;
use Phantom\Model\Mail;
use Phantom\Model\Model;
use Phantom\Model\Route;
use Phantom\Repository\Repository;
use Phantom\Validator\Validator;
use Phantom\View;

abstract class Controller extends Validator
{
    protected static $config = [];
    protected static $route = [];
    protected $request;
    protected $view;
    protected $user = null;
    protected $mail;
    private $userModel;
    protected $model;

    public static function initConfiguration(Config $config, Route $route): void
    {
        self::$config = $config;
        self::$route = $route;
    }

    public function __construct(Request $request)
    {
        if (empty(self::$config->get('db'))) {
            throw new ConfigurationException('Configuration error');
        }

        Model::initConfiguration(self::$config);
        Repository::initConfiguration(self::$config->get('db'));
        User::initConfiguration(self::$config);

        $this->mail = new Mail(self::$config->get('mail'));
        $this->userModel = new User();

        if ($id = Session::get('user:id')) {
            $this->user = $this->userModel->findById(User::ID());
        }

        $this->request = $request;
        $this->view = new View($this->user, self::$route);
    }

    public function run(): void
    {
        try {
            $action = $this->action();

            if (!method_exists($this, $action)) {
                Session::error('Akcja do której chciałeś otrzymać dostęp nie istnieje');
                $this->redirect("home");
            }

            $this->$action();
        } catch (StorageException $e) {
            $this->view->render('error', ['message' => $e->getMessage()]);
        }
    }

    protected function redirect(string $to, array $params = []): void
    {
        $location = self::$route->get($to);

        if (count($params)) {
            $queryParams = [];
            foreach ($params as $key => $value) {
                if (gettype($value) == "integer") {
                    $queryParams[] = urlencode($key) . '=' . $value;
                } else {
                    $queryParams[] = urlencode($key) . '=' . urlencode($value);
                }
            }

            $location .= ($queryParams = "&" . implode('&', $queryParams));
        }

        header("Location: " . $location);
        exit();
    }

    protected function action(): string
    {
        $action = $this->request->getParam('action', "index");
        $action = ($action == "index") ? $action : $action . "Action";
        return $action;
    }

    // ===== ===== ===== ===== =====

    final protected function guest(): void
    {
        if ($this->user != null) {
            Session::error("Strona, na którą próbowałeś się dostać, jest dostępna wyłącznie dla użytkowników nie zalogowanych.");
            $this->redirect('home');
        }
    }

    final protected function requireLogin(): void
    {
        if ($this->user == null) {
            Session::error("Strona, na którą próbowałeś się dostać, wymaga zalogowania się");
            $this->redirect('auth.login');
        }
    }

    final protected function requireAdmin(): void
    {
        $this->requireLogin();

        if (!$this->user->isAdmin()) {
            Session::error("Nie posiadasz wystarczających uprawnień do akcji, którą chciałeś wykonać");
            $this->redirect('home');
        }
    }
}
