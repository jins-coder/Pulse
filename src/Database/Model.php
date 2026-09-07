<?php

declare(strict_types=1);

namespace Pulse\Database;

use PDO;
use PDOStatement;
use Pulse\Container\Container;

class QueryBuilder
{
    protected array $wheres = [];
    protected array $bindings = [];
    protected ?int $limitValue = null;
    protected ?int $offsetValue = null;
    protected array $orders = [];
    protected bool $multiTenant = true;

    public function __construct(
        protected Connection $connection,
        protected string $table
    ) {}

    public function withoutTenancy(): self
    {
        $this->multiTenant = false;
        return $this;
    }

    public function where(string $column, mixed $operator = null, mixed $value = null): self
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
        ];
        $this->bindings[] = $value;
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orders[] = "{$column} " . strtoupper($direction);
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limitValue = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offsetValue = $offset;
        return $this;
    }

    public function get(): array
    {
        $this->applyTenantScope();
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($this->wheres)) {
            $clauses = array_map(fn($w) => "{$w['column']} {$w['operator']} ?", $this->wheres);
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }

        if (!empty($this->orders)) {
            $sql .= " ORDER BY " . implode(', ', $this->orders);
        }

        if ($this->limitValue !== null) {
            $sql .= " LIMIT {$this->limitValue}";
        }

        if ($this->offsetValue !== null) {
            $sql .= " OFFSET {$this->offsetValue}";
        }

        return $this->connection->query($sql, $this->bindings)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(): int
    {
        $this->applyTenantScope();
        $sql = "SELECT COUNT(*) as aggregate FROM {$this->table}";
        if (!empty($this->wheres)) {
            $clauses = array_map(fn($w) => "{$w['column']} {$w['operator']} ?", $this->wheres);
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }
        $row = $this->connection->query($sql, $this->bindings)->fetch(PDO::FETCH_ASSOC);
        return (int)($row['aggregate'] ?? 0);
    }

    public function paginate(int $perPage = 15, int $page = 1): Paginator
    {
        $total = $this->count();
        $offset = ($page - 1) * $perPage;
        $items = $this->limit($perPage)->offset($offset)->get();
        $lastPage = (int)ceil($total / max(1, $perPage));

        return new Paginator($items, $total, $perPage, $page, max(1, $lastPage));
    }

    public function first(): ?array
    {
        $results = $this->limit(1)->get();
        return $results[0] ?? null;
    }

    public function insert(array $values): int
    {
        if ($this->multiTenant && TenantContext::has() && !isset($values['tenant_id'])) {
            $values['tenant_id'] = TenantContext::get();
        }

        $columns = implode(', ', array_keys($values));
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $this->connection->query($sql, array_values($values));
        return (int)$this->connection->getPdo()->lastInsertId();
    }

    public function update(array $values): int
    {
        $this->applyTenantScope();
        $setClauses = [];
        $updateBindings = [];

        foreach ($values as $col => $val) {
            $setClauses[] = "{$col} = ?";
            $updateBindings[] = $val;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses);

        if (!empty($this->wheres)) {
            $clauses = array_map(fn($w) => "{$w['column']} {$w['operator']} ?", $this->wheres);
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }

        $allBindings = array_merge($updateBindings, $this->bindings);
        $stmt = $this->connection->query($sql, $allBindings);
        return $stmt->rowCount();
    }

    public function delete(): int
    {
        $this->applyTenantScope();
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->wheres)) {
            $clauses = array_map(fn($w) => "{$w['column']} {$w['operator']} ?", $this->wheres);
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }

        $stmt = $this->connection->query($sql, $this->bindings);
        return $stmt->rowCount();
    }

    protected function applyTenantScope(): void
    {
        if ($this->multiTenant && TenantContext::has()) {
            foreach ($this->wheres as $w) {
                if ($w['column'] === 'tenant_id') {
                    return;
                }
            }
            array_unshift($this->wheres, [
                'column' => 'tenant_id',
                'operator' => '=',
                'value' => TenantContext::get(),
            ]);
            array_unshift($this->bindings, TenantContext::get());
        }
    }
}

abstract class Model
{
    protected static ?Connection $resolver = null;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $attributes = [];
    protected bool $isMultiTenant = true;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public static function setConnectionResolver(Connection $connection): void
    {
        self::$resolver = $connection;
    }

    public static function getConnection(): Connection
    {
        if (self::$resolver === null) {
            self::$resolver = Container::getInstance()->resolve(Connection::class);
        }
        return self::$resolver;
    }

    public static function query(): QueryBuilder
    {
        $instance = new static();
        $builder = self::getConnection()->table($instance->getTable());
        if (!$instance->isMultiTenant) {
            $builder->withoutTenancy();
        }
        return $builder;
    }

    public static function all(): array
    {
        $rows = static::query()->get();
        return array_map(fn($row) => new static($row), $rows);
    }

    public static function find(int|string $id): ?static
    {
        $instance = new static();
        $row = static::query()->where($instance->primaryKey, $id)->first();
        return $row ? new static($row) : null;
    }

    public static function create(array $attributes): static
    {
        $instance = new static($attributes);
        $instance->save();
        return $instance;
    }

    public function getTable(): string
    {
        if (isset($this->table)) {
            return $this->table;
        }
        $short = (new \ReflectionClass($this))->getShortName();
        return strtolower($short) . 's';
    }

    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
        return $this;
    }

    public function save(): bool
    {
        if (isset($this->attributes[$this->primaryKey])) {
            $id = $this->attributes[$this->primaryKey];
            static::query()->where($this->primaryKey, $id)->update($this->attributes);
        } else {
            $id = static::query()->insert($this->attributes);
            $this->attributes[$this->primaryKey] = $id;
        }
        return true;
    }

    public function delete(): bool
    {
        if (isset($this->attributes[$this->primaryKey])) {
            static::query()->where($this->primaryKey, $this->attributes[$this->primaryKey])->delete();
            return true;
        }
        return false;
    }

    public function __get(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }
}
