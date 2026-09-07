<?php

declare(strict_types=1);

namespace Pulse\Database;

class Schema
{
    public static function create(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        $sql = $blueprint->toSql();
        Model::getConnection()->query($sql);
    }

    public static function dropIfExists(string $table): void
    {
        $sql = "DROP TABLE IF EXISTS {$table};";
        Model::getConnection()->query($sql);
    }
}

class Blueprint
{
    protected array $columns = [];

    public function __construct(public readonly string $table) {}

    public function id(): self
    {
        $this->columns[] = "id INTEGER PRIMARY KEY AUTOINCREMENT";
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

    public function boolean(string $name): self
    {
        $this->columns[] = "{$name} BOOLEAN NOT NULL DEFAULT 0";
        return $this;
    }

    public function tenantId(): self
    {
        $this->columns[] = "tenant_id VARCHAR(64) NOT NULL DEFAULT 'default'";
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

abstract class Migration
{
    abstract public function up(): void;
    abstract public function down(): void;
}
