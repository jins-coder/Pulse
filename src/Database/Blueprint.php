<?php

declare(strict_types=1);

namespace Pulse\Database;

class Blueprint
{
    protected array $columns = [];

    public function __construct(public readonly string $table) {}

    public function id(): self
    {
        $this->columns[] = "id INTEGER PRIMARY KEY AUTOINCREMENT";
        return $this;
    }

    public function tenantId(string $name = 'tenant_id'): self
    {
        $this->columns[] = "{$name} VARCHAR(64) NOT NULL DEFAULT 'default'";
        return $this;
    }

    public function string(string $name, int $length = 255): self
    {
        $this->columns[] = "{$name} VARCHAR({$length}) NOT NULL";
        return $this;
    }

    public function text(string $name): self
    {
        $this->columns[] = "{$name} TEXT";
        return $this;
    }

    public function integer(string $name): self
    {
        $this->columns[] = "{$name} INTEGER NOT NULL DEFAULT 0";
        return $this;
    }

    public function float(string $name): self
    {
        $this->columns[] = "{$name} REAL NOT NULL DEFAULT 0.0";
        return $this;
    }

    public function decimal(string $name, int $precision = 8, int $scale = 2): self
    {
        $this->columns[] = "{$name} NUMERIC({$precision}, {$scale}) NOT NULL DEFAULT 0.00";
        return $this;
    }

    public function boolean(string $name): self
    {
        $this->columns[] = "{$name} BOOLEAN NOT NULL DEFAULT 0";
        return $this;
    }

    public function timestamps(): self
    {
        $this->columns[] = "created_at DATETIME DEFAULT CURRENT_TIMESTAMP";
        $this->columns[] = "updated_at DATETIME DEFAULT CURRENT_TIMESTAMP";
        return $this;
    }

    public function toSql(): string
    {
        $cols = implode(",\n    ", $this->columns);
        return "CREATE TABLE IF NOT EXISTS {$this->table} (\n    {$cols}\n);";
    }
}
