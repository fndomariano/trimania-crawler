<?php

namespace Trimania\Core;

use Trimania\Core\Interfaces\DatabaseInterface;

class Database implements DatabaseInterface
{
    private $pdo = false;

    private $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function connect()
    {
        $dsn = $this->dsn();

        $config = $this->config;

        try {
            $this->pdo = new \PDO($dsn, $config['user'], $config['pass']);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        return $this->pdo;
    }

    private function dsn()
    {
        $config = $this->config;

        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['base']}";

        return $dsn;
    }
    
    public function executeSelect(string $query)
    {
        $this->connect();

        if ($query == '') {
            echo 'Query is empty!';
        }

        $result = $this->pdo->query($query);
        
        if (!$result) {
            echo $this->pdo->errorInfo()[2];
        }

        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function executeInsert(string $query)
    {
        $this->connect();

        if ($query == '') {
            echo 'Query is empty!';
        }

        $result = $this->pdo->query($query);

        if (!$result) {
            echo $this->pdo->errorInfo()[2];
        }

        return $this->pdo->lastInsertId();
    }
}
