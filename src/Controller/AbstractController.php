<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use App\Helper\Request;
use App\Helper\Session;
use App\Model\User;
use App\Repository\AbstractRepository;
use App\View;

abstract class AbstractController
{
    protected static $configuration = [];
    protected static $route = [];

    protected $request;
    protected $view;
    protected $user;

    public static function initConfiguration(array $configuration, array $route): void
    {
        self::$configuration = $configuration;
        self::$route = $route;
    }

    public function __construct(Request $request)
    {
        if (empty(self::$configuration['db'])) {
            throw new ConfigurationException('Configuration error');
        }

        AbstractRepository::initConfiguration(self::$configuration['db']);
        View::setStyle($this->style ?? null);

        $this->request = $request;
        $this->user = new User();

        if ($this->user->id ?? $this->user = null);
        $this->view = new View($this->user, self::$route);
    }

    final public function run(): void
    {
        try {
            $action = $this->action() . 'Action';
            if (!method_exists($this, $action)) {
                Session::set("error", 'Akcja do której chciałeś otrzymać dostęp nie istnieje');
                $this->redirect(self::$route['home']);
            }
            $this->$action();
        } catch (StorageException $e) {
            $this->view->render('error', ['message' => $e->getMessage()]);
        }
    }

    final protected function redirect(string $to, array $params = []): void
    {
        $location = $to;

        if (count($params)) {
            $queryParams = [];
            foreach ($params as $key => $value) {
                $queryParams[] = urlencode($key) . '=' . urlencode($value);
            }

            $location .= ($queryParams = "&" . implode('&', $queryParams));
        }

        header("Location: " . $location);
        exit();
    }

    final private function action(): string
    {
        return $this->request->getParam('action');
    }

    // ===== ===== ===== ===== =====

    final protected function requireLogin()
    {
        if ($this->user == null) {
            Session::set('lastPage', $this->request->queryString());
            Session::set("error", "Strona, na którą próbowałeś się dostać, wymaga zalogowania się");
            $this->redirect(self::$route['auth.login']);
        }
    }

    final protected function requireAdmin()
    {
        $this->requireLogin();
        Session::clear('lastPage');

        if (!$this->user->isAdmin()) {
            Session::set("error", "Nie posiadasz wystarczających uprawnień do akcji, którą chciałeś wykonać");
            $this->redirect(self::$route['home']);
        }
    }

    protected function uploadFile($path, $FILE)
    {
        $target_dir = $path;
        $type = strtolower(pathinfo($FILE['name'], PATHINFO_EXTENSION));
        $target_file = $target_dir . basename($FILE["name"]);

        if (move_uploaded_file($FILE["tmp_name"], $target_file)) {
            return true;
        } else {
            Session::set('error', 'Przepraszamy, wystąpił problem w trakcie wysyłania pliku');
            return false;
        }
    }
}
