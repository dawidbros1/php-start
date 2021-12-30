<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use App\Helper\Request;
use App\Helper\Session;
use App\Model\Config;
use App\Model\Mail;
use App\Model\Route;
use App\Model\User;
use App\Repository\Repository;
use App\Repository\UserRepository;
use App\Validator\Validator;
use App\View;

abstract class Controller extends Validator
{
    protected static $config = [];
    protected static $route = [];

    protected $userRepository;
    protected $hashMethod;
    protected $request;
    protected $view;
    protected $user = null;

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

        Repository::initConfiguration(self::$config->get('db'));
        Mail::initConfiguration(self::$config->get('mail'));

        $this->hashMethod = self::$config->get('hash.method');
        $this->userRepository = new UserRepository();

        if ($id = Session::get('user:id')) {
            $this->user = $this->userRepository->get((int) $id);
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
        return $this->request->getParam('action', "home");
    }

    // ===== ===== ===== ===== =====

    final protected function guest()
    {
        if ($this->user != null) {
            Session::set("error", "Strona, na którą próbowałeś się dostać, jest dostępna wyłącznie dla użytkowników nie zalogowanych.");
            $this->redirect(self::$route->get('home'));
        }
    }

    final protected function requireLogin()
    {
        if ($this->user == null) {
            Session::set('lastPage', $this->request->queryString());
            Session::set("error", "Strona, na którą próbowałeś się dostać, wymaga zalogowania się");
            $this->redirect(self::$route->get('auth.login'));
        }
    }

    final protected function requireAdmin()
    {
        $this->requireLogin();
        Session::clear('lastPage');

        if (!$this->user->isAdmin()) {
            Session::set("error", "Nie posiadasz wystarczających uprawnień do akcji, którą chciałeś wykonać");
            $this->redirect(self::$route->get('home'));
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

    protected function hash($param, $method = null)
    {
        return hash($method ?? $this->hashMethod, $param);
    }

    protected function hashFile($file)
    {
        $type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name = $this->hash(date('Y-m-d H:i:s') . "_" . $name);
        $file['name'] = $name . '.' . $type;
        return $file;
    }
}
