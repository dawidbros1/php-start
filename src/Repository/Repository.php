<?php

declare (strict_types = 1);

namespace App\Repository;

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use PDO;
use PDOException;

abstract class Repository
{
    protected $pdo;
    protected static $config;
    protected static $user_id;

    public static function initConfiguration($config)
    {
        self::$config = $config;
    }
    
    public function __construct()
    {
        try {
            $this->validateConfig(self::$config);
            $this->createConnection(self::$config);
        } catch (PDOException $e) {
            throw new StorageException('Connection error');
        }
    }

    private function createConnection(array $config): void
    {
        $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        $this->pdo = new PDO($dsn, $config['user'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    private function validateConfig(array $config): void
    {
        if (
            empty($config['database']) ||
            empty($config['host']) ||
            empty($config['user']) ||
            !isset($config['password'])
        ) {
            throw new ConfigurationException('Storage configuration error');
        }
    }

    protected function quoteAll(array $data)
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->pdo->quote($value);
        }

        return $data;
    }
}
