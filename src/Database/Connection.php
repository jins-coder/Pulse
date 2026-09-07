<?php

declare(strict_types=1);

namespace Pulse\Database;

use PDO;
use PDOStatement;

class Connection
{
    protected ?PDO $pdo = null;
    protected array $queryLog = [];

    public function __construct(
        protected string $dsn,
        protected ?string $username = null,
        protected ?string $password = null,
        protected array $options = []
    ) {}

    public function getPdo(): PDO
    {
        if ($this->pdo === null) {
            $defaultOptions = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->pdo = new PDO($this->dsn, $this->username, $this->password, array_replace($defaultOptions, $this->options));
        }
        return $this->pdo;
    }

    public function query(string $sql, array $bindings = []): PDOStatement
    {
        $start = microtime(true);
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute($bindings);
        $duration = (microtime(true) - $start) * 1000;

        $this->queryLog[] = [
            'sql' => $sql,
            'bindings' => $bindings,
            'time_ms' => round($duration, 2),
        ];

        return $stmt;
    }

    public function getQueryLog(): array
    {
        return $this->queryLog;
    }

    public function lastInsertId(): string|false
    {
        return $this->getPdo()->lastInsertId();
    }
}
