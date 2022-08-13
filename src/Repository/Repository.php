<?php

declare (strict_types = 1);

namespace App\Repository;

use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use PDO;
use PDOException;

abstract class Repository
{
    protected static $pdo;
    protected static $config;
    protected $table;

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
        self::$pdo = new PDO($dsn, $config['user'], $config['password'], [
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

    // ===== GETTERS ===== //

    public function get(array $input, $options)
    {
        $conditions = $this->getConditions($input);
        $stmt = self::$pdo->prepare("SELECT * FROM $this->table WHERE $conditions $options");
        $stmt->execute($input);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getAll(array $input, $options): ?array
    {
        $conditions = $this->getConditions($input);
        $stmt = self::$pdo->prepare("SELECT * FROM $this->table WHERE $conditions $options");
        $stmt->execute($input);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    // ===== Create ===== //

    public function create($object)
    {
        $object->escape();
        $data = $object->getArray($object->fillable);
        $params = "";
        $values = "";

        for ($i = 0; $i < count($data); $i++) {
            $params = $params . "" . key($data) . ($i == count($data) - 1 ? "" : ", ");
            $values = $values . ":" . key($data) . ($i == count($data) - 1 ? "" : ", ");
            next($data);
        }

        try {
            $sql = "INSERT INTO $this->table ($params) VALUES ($values)";
            $stmt = self::$pdo->prepare($sql);
            $stmt->execute($data);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się dodać nowej zawartości', 400, $e);
        }
    }

    public function update($data)
    {
        $params = "";

        for ($i = 0; $i < count($data); $i++) {
            $params = $params . key($data) . "=:" . key($data) . ($i == count($data) - 1 ? "" : ", ");
            next($data);
        }

        $sql = "UPDATE $this->table SET $params WHERE id=:id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($data);
    }

    public function delete(int $id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        self::$pdo->prepare($sql)->execute(['id' => $id]);
    }

    private function getConditions(array $input)
    {
        $conditions = "";

        foreach ($input as $key => $value) {
            $conditions .= $conditions . " " . $key . "=:" . $key . " AND";
        }

        $conditions .= " 1";

        return $conditions;
    }
}
