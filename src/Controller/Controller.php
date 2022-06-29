<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use App\Helper\Request;
use App\Helper\Session;
use App\Model\Config;
use App\Model\Mail;
use App\Model\Model;
use App\Model\Route;
use App\Model\User;
use App\Repository\Repository;
use App\Validator\Validator;
use App\View;

abstract class Controller extends Validator
{
    protected static $config = [];
    protected static $route = [];

    protected $request;
    protected $view;
    protected $user = null;
    protected $mail;

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

        Model::initConfiguration(self::$config->get('hash.method'));
        Repository::initConfiguration(self::$config->get('db'));

        $this->mail = new Mail(self::$config->get('mail'));
        $this->userModel = new User();

        if ($id = Session::get('user:id')) {
            $this->user = $this->userModel->find((int) $id);
        }

        $this->request = $request;
        $this->view = new View($this->user, self::$route);
    }

    public function run(): void
    {
        try {
            $action = $this->action() . 'Action';
            if (!method_exists($this, $action)) {
                Session::set("error", 'Akcja do której chciałeś otrzymać dostęp nie istnieje');
                $this->redirect("./");
            }

            $this->$action();
        } catch (StorageException $e) {
            $this->view->render('error', ['message' => $e->getMessage()]);
        }
    }

    protected function redirect(string $to, array $params = []): void
    {
        $location = $to;

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

    final private function action(): string
    {
        return $this->request->getParam('action', "home");
    }

    // ===== ===== ===== ===== =====

    final protected function guest(): void
    {
        if ($this->user != null) {
            Session::set("error", "Strona, na którą próbowałeś się dostać, jest dostępna wyłącznie dla użytkowników nie zalogowanych.");
            $this->redirect(self::$route->get('home'));
        }
    }

    final protected function requireLogin(): void
    {
        if ($this->user == null) {
            Session::set('lastPage', $this->request->queryString());
            Session::set("error", "Strona, na którą próbowałeś się dostać, wymaga zalogowania się");
            $this->redirect(self::$route->get('auth.login'));
        }
    }

    final protected function requireAdmin(): void
    {
        $this->requireLogin();
        Session::clear('lastPage');

        if (!$this->user->isAdmin()) {
            Session::set("error", "Nie posiadasz wystarczających uprawnień do akcji, którą chciałeś wykonać");
            $this->redirect(self::$route->get('home'));
        }
    }
}
