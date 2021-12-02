<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\ConfigurationException;
use App\Exception\NotFoundException;
use App\Exception\StorageException;
use App\Helper\Request;
use App\View;

use App\Repository\AbstractRepository;

abstract class AbstractController
{
    //! Później ustawić inną akcję domyślną
    protected const DEFAULT_ACTION = 'register';

    protected static $configuration = [];

    protected $repository;
    protected $request;
    protected $view;

    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }

    public function __construct(Request $request)
    {
        if (empty(self::$configuration['db'])) {
            throw new ConfigurationException('Configuration error');
        }

        // AbstractRepository::initConfiguration(self::$configuration);

        $this->request = $request;
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

    final protected function redirect(string $to, array $params): void
    {
        $location = $to;

        if (count($params)) {
            $queryParams = [];
            foreach ($params as $key => $value) {
                $queryParams[] = urlencode($key) . '=' . urlencode($value);
            }
            $queryParams = implode('&', $queryParams);
            $location .= '?' . $queryParams;
        }

        header("Location: $location");
        exit();
    }

    final private function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}