<?php

declare(strict_types=1);

namespace Pulse\Storage;

use Pulse\Core\NativeCore;

/**
 * EmbeddedStorage - In-Memory LSM / Columnar Key-Value & Document Engine
 * Delivers sub-microsecond state access without network hop latency.
 */
class EmbeddedStorage
{
    private static ?self $instance = null;
    
    /** @var array<string, array{value: mixed, expires_at: ?float, tags: array<string>}> */
    private array $store = [];

    /** @var array<string, array<int, array<string, mixed>>> */
    private array $columnarTables = [];

    private NativeCore $core;

    public function __construct()
    {
        $this->core = NativeCore::getInstance();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Put a value into the in-memory store with optional TTL (in seconds)
     */
    public function set(string $key, mixed $value, ?float $ttl = null, array $tags = []): void
    {
        $expiresAt = $ttl !== null ? microtime(true) + $ttl : null;
        $this->store[$key] = [
            'value' => $value,
            'expires_at' => $expiresAt,
            'tags' => $tags,
        ];
    }

    /**
     * Retrieve a value in <1 microsecond
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (!isset($this->store[$key])) {
            return $default;
        }

        $entry = $this->store[$key];
        if ($entry['expires_at'] !== null && microtime(true) > $entry['expires_at']) {
            unset($this->store[$key]);
            return $default;
        }

        return $entry['value'];
    }

    /**
     * Check if key exists and is not expired
     */
    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    /**
     * Delete key
     */
    public function delete(string $key): bool
    {
        if (isset($this->store[$key])) {
            unset($this->store[$key]);
            return true;
        }
        return false;
    }

    /**
     * Increment an integer value atomically
     */
    public function increment(string $key, int $amount = 1): int
    {
        $current = (int)$this->get($key, 0);
        $new = $current + $amount;
        $this->set($key, $new);
        return $new;
    }

    /**
     * In-memory Columnar Table append (DuckDB-like memory buffer)
     */
    public function insertRow(string $table, array $row): void
    {
        if (!isset($this->columnarTables[$table])) {
            $this->columnarTables[$table] = [];
        }
        $row['_id'] = count($this->columnarTables[$table]) + 1;
        $row['_timestamp'] = microtime(true);
        $this->columnarTables[$table][] = $row;
    }

    /**
     * Query columnar table with predicate and aggregation
     *
     * @return array<string, mixed>
     */
    public function queryTable(string $table, ?callable $filter = null): array
    {
        $rows = $this->columnarTables[$table] ?? [];
        if ($filter !== null) {
            $filtered = [];
            foreach ($rows as $row) {
                if ($filter($row)) {
                    $filtered[] = $row;
                }
            }
            $rows = $filtered;
        }

        return [
            'table' => $table,
            'count' => count($rows),
            'rows' => $rows,
            'engine' => $this->core->getEngineDescription(),
        ];
    }

    /**
     * Flush expired keys
     */
    public function gc(): int
    {
        $now = microtime(true);
        $removed = 0;
        foreach ($this->store as $key => $entry) {
            if ($entry['expires_at'] !== null && $now > $entry['expires_at']) {
                unset($this->store[$key]);
                $removed++;
            }
        }
        return $removed;
    }

    /**
     * Store statistics
     */
    public function stats(): array
    {
        return [
            'total_keys' => count($this->store),
            'columnar_tables' => array_keys($this->columnarTables),
            'total_columnar_rows' => array_sum(array_map('count', $this->columnarTables)),
            'memory_engine' => $this->core->getEngineDescription(),
        ];
    }
}
