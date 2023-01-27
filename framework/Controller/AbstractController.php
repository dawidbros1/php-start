<?php

declare(strict_types=1);

namespace Phantom\Controller;

use App\Model\User;
use Phantom\Exception\AppException;
use Phantom\Exception\ConfigurationException;
use Phantom\Exception\StorageException;
use Phantom\Helper\Session;
use Phantom\Model\AbstractModel;
use Phantom\Model\Config;
use Phantom\Model\Route;
use Phantom\Repository\DBFinder;
use Phantom\RedirectToRoute;
use Phantom\Repository\AbstractRepository;
use Phantom\Request\Request;
use Phantom\View;

abstract class AbstractController
{
    protected static Config $config;
    protected static Route $route;
    protected $request;
    protected $view;
    protected $user = null;
    protected $mail;
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

        AbstractModel::initConfiguration(self::$config);
        AbstractRepository::initConfiguration(self::$config->get('db'));
        User::initConfiguration(self::$config);

        if (Session::get('user:id')) {
            $this->user = DBFinder::getInstance('users')->findById(User::ID(), User::class);
        }

        $this->request = $request;
    }

    # Method runs selected action form controller
    public function run(): void
    {
        try {
            $action = $this->action(); // get action

            if (!method_exists($this, $action)) { // checks if action exists
                Session::error('Akcja do której chciałeś otrzymać dostęp nie istnieje');
                $this->redirect("home")->redirect();
            }

            $result = $this->$action(); // run action

            if (gettype($result) !== "object") {
                throw new AppException("Invalid type returned [" . gettype($result) . "]! Controller must return object.");
            }

            switch ($result::class) {
                case "Phantom\View": {
                        $result->render();
                        break;
                    }
                case "Phantom\RedirectToRoute": {
                        $result->redirect();
                        break;
                    }
            }

        } catch (StorageException $e) {
            if (self::$config->get('env') == "dev") {
                dump($e);
            } else {
                dump($e->getMessage());
            }
            die();
        }
    }

    # Method returns action from request
    protected function action(): string
    {
        $action = $this->request->getParam('action', "index");
        $action = ($action == "index") ? $action : $action . "Action";
        return $action;
    }

    # Method require to user was not logged in
    protected function forGuest(): void
    {
        if ($this->user != null) {
            Session::error("Strona, na którą próbowałeś się dostać, jest dostępna wyłącznie dla użytkowników nie zalogowanych.");
            $this->redirect('home', [], true);
        }
    }

    # Method require to user was logged in
    protected function forLogged(): void
    {
        if ($this->user == null) {
            Session::error("Strona, na którą próbowałeś się dostać, wymaga zalogowania się");
            $this->redirect('auth.login', [], true);
        }
    }
    # Method required to logged in user has role admin
    protected function forAdmin(): void
    {
        $this->forLogged();

        if (!$this->user->isAdmin()) {
            Session::error("Nie posiadasz wystarczających uprawnień do akcji, którą chciałeś wykonać");
            $this->redirect('home', [], true);
        }
    }

    # Method returns View
    protected function render(string $page, array $params = []): View
    {
        return new View($this->user, self::$route, $page, $params);
    }

    # Method returns RedirectToRoute
    protected function redirect(string $to, array $params = [], bool $execute = false): RedirectToRoute
    {
        $redirectToRoute = new RedirectToRoute(self::$route, $to, $params);

        if ($execute === true) {
            $redirectToRoute->redirect();
            exit();
        }

        return $redirectToRoute;
    }

    protected function redirectToLastPage()
    {
        header("Location: " . $this->request->lastPage());
        exit();
    }
}