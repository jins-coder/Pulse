<?php

declare(strict_types=1);

namespace Pulse\Compiler;

/**
 * Memory-Mapped Bytecode Cache for compiled opcode bundles.
 */
class BytecodeCache
{
    protected array $cache = [];
    protected int $hits = 0;
    protected int $misses = 0;

    public function set(string $key, string $bytecode, array $metadata = []): void
    {
        $this->cache[$key] = [
            'bytecode' => $bytecode,
            'hash' => sha1($bytecode),
            'size_bytes' => strlen($bytecode),
            'metadata' => $metadata,
            'cached_at' => microtime(true),
        ];
    }

    public function get(string $key): ?array
    {
        if (isset($this->cache[$key])) {
            $this->hits++;
            return $this->cache[$key];
        }
        $this->misses++;
        return null;
    }

    public function has(string $key): bool
    {
        return isset($this->cache[$key]);
    }

    public function getStats(): array
    {
        $total = $this->hits + $this->misses;
        return [
            'entries_count' => count($this->cache),
            'hits' => $this->hits,
            'misses' => $this->misses,
            'hit_rate' => $total > 0 ? round(($this->hits / $total) * 100, 2) . '%' : '0%',
        ];
    }

    public function clear(): void
    {
        $this->cache = [];
        $this->hits = 0;
        $this->misses = 0;
    }
}
