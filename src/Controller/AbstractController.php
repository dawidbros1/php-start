<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Exception\ConfigurationException;
use App\Exception\NotFoundException;
use App\Exception\StorageException;
use App\Helper\Request;
use App\Model\User;
use App\Repository\AbstractRepository;
use App\View;

abstract class AbstractController
{
    //! Później ustawić inną akcję domyślną
    protected const DEFAULT_ACTION = 'register';

    protected static $configuration = [];

    protected $repository;
    protected $request;
    protected $view;
    protected $user;

    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }

    public function __construct(Request $request)
    {
        if (empty(self::$configuration['db'])) {
            throw new ConfigurationException('Configuration error');
        }

        AbstractRepository::initConfiguration(self::$configuration['db']);

        $this->request = $request;
        $this->user = new User();
        if ($this->user->id ?? $this->user = null);
        $this->view = new View();
    }

    final public function run(): void
    {
        try {
            $action = $this->action() . 'Action';
            if (!method_exists($this, $action)) {
                $action = self::DEFAULT_ACTION . 'Action';
            }
            $this->$action();
        } catch (StorageException $e) {
            $this->view->render('error', ['message' => $e->getMessage()]);
        } catch (NotFoundException $e) {
            $this->redirect('/', ['error' => 'noteNotFound']);
        }
    }

    final protected function redirect(string $to, array $params = []): void
    {
        $location = "?action=" . $to;

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
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}