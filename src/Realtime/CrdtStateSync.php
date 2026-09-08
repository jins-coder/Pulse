<?php

declare(strict_types=1);

namespace Pulse\Realtime;

/**
 * Distributed Conflict-Free Replicated Data Type (CRDT) State Synchronizer for Pulse v3.0 (Horizon).
 * Implements Last-Write-Wins Register (LWW-Register) and Positive-Negative Counter (PN-Counter) for Edge node state replication.
 */
class CrdtStateSync
{
    private static ?CrdtStateSync $instance = null;
    protected string $nodeId;
    protected array $registers = []; // [key => ['value' => mixed, 'timestamp' => float, 'node_id' => string]]
    protected array $counters = [];  // [key => ['P' => [node => int], 'N' => [node => int]]]

    public function __construct(?string $nodeId = null)
    {
        $this->nodeId = $nodeId ?? 'node_' . bin2hex(random_bytes(4));
        self::$instance = $this;
    }

    public static function getInstance(): CrdtStateSync
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getNodeId(): string
    {
        return $this->nodeId;
    }

    // --- LWW-Register (Last-Write-Wins) ---
    public function set(string $key, mixed $value, ?float $timestamp = null): void
    {
        $ts = $timestamp ?? microtime(true);
        $this->registers[$key] = [
            'value' => $value,
            'timestamp' => $ts,
            'node_id' => $this->nodeId,
        ];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->registers[$key]['value'] ?? $default;
    }

    public function mergeRegisters(array $remoteRegisters): void
    {
        foreach ($remoteRegisters as $key => $remoteEntry) {
            $localEntry = $this->registers[$key] ?? null;
            if ($localEntry === null || $remoteEntry['timestamp'] > $localEntry['timestamp']) {
                $this->registers[$key] = $remoteEntry;
            }
        }
    }

    // --- PN-Counter (Positive-Negative Counter) ---
    public function incrementCounter(string $key, int $amount = 1): void
    {
        if (!isset($this->counters[$key])) {
            $this->counters[$key] = ['P' => [], 'N' => []];
        }
        $this->counters[$key]['P'][$this->nodeId] = ($this->counters[$key]['P'][$this->nodeId] ?? 0) + $amount;
    }

    public function decrementCounter(string $key, int $amount = 1): void
    {
        if (!isset($this->counters[$key])) {
            $this->counters[$key] = ['P' => [], 'N' => []];
        }
        $this->counters[$key]['N'][$this->nodeId] = ($this->counters[$key]['N'][$this->nodeId] ?? 0) + $amount;
    }

    public function getCounter(string $key): int
    {
        if (!isset($this->counters[$key])) {
            return 0;
        }
        $pos = array_sum($this->counters[$key]['P']);
        $neg = array_sum($this->counters[$key]['N']);
        return $pos - $neg;
    }

    public function mergeCounters(array $remoteCounters): void
    {
        foreach ($remoteCounters as $key => $remoteEntry) {
            if (!isset($this->counters[$key])) {
                $this->counters[$key] = ['P' => [], 'N' => []];
            }
            foreach ($remoteEntry['P'] ?? [] as $node => $val) {
                $this->counters[$key]['P'][$node] = max($this->counters[$key]['P'][$node] ?? 0, $val);
            }
            foreach ($remoteEntry['N'] ?? [] as $node => $val) {
                $this->counters[$key]['N'][$node] = max($this->counters[$key]['N'][$node] ?? 0, $val);
            }
        }
    }

    public function exportState(): array
    {
        return [
            'node_id' => $this->nodeId,
            'registers' => $this->registers,
            'counters' => $this->counters,
            'exported_at' => microtime(true),
        ];
    }
}
