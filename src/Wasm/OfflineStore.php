<?php

declare(strict_types=1);

namespace Pulse\Wasm;

/**
 * Offline-First Local Store & Vector Clock Reconciliation Engine for Pulse v4.0 (Infinity).
 */
class OfflineStore
{
    protected array $records = [];
    protected array $vectorClock = [];
    protected string $clientId;

    public function __construct(?string $clientId = null)
    {
        $this->clientId = $clientId ?? 'client_' . bin2hex(random_bytes(4));
        $this->vectorClock[$this->clientId] = 0;
    }

    public function put(string $key, mixed $value): array
    {
        $this->vectorClock[$this->clientId]++;
        $entry = [
            'key' => $key,
            'value' => $value,
            'client_id' => $this->clientId,
            'version' => $this->vectorClock[$this->clientId],
            'timestamp' => microtime(true),
            'synced' => false,
        ];
        $this->records[$key] = $entry;
        return $entry;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->records[$key]['value'] ?? $default;
    }

    public function getPendingSyncRecords(): array
    {
        return array_values(array_filter($this->records, fn($r) => !$r['synced']));
    }

    public function markSynced(string $key): void
    {
        if (isset($this->records[$key])) {
            $this->records[$key]['synced'] = true;
        }
    }

    public function reconcile(array $remoteRecords): array
    {
        $applied = 0;
        foreach ($remoteRecords as $remote) {
            $key = $remote['key'] ?? null;
            if (!$key) continue;

            $local = $this->records[$key] ?? null;
            if ($local === null || ($remote['timestamp'] ?? 0) > ($local['timestamp'] ?? 0)) {
                $this->records[$key] = array_merge($remote, ['synced' => true]);
                $applied++;
            }
        }

        return [
            'reconciled_count' => $applied,
            'total_records' => count($this->records),
            'timestamp' => microtime(true),
        ];
    }
}
