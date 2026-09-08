<?php

declare(strict_types=1);

namespace Pulse\Queue;

/**
 * Dead Letter Queue (DLQ) with error diagnostics, classification, and replay.
 */
class DeadLetterQueue
{
    protected array $failedJobs = [];

    public function recordFailure(Job $job, \Throwable $exception, array $metadata = []): void
    {
        $this->failedJobs[] = [
            'id' => 'dlq_' . bin2hex(random_bytes(6)),
            'job' => $job,
            'job_class' => get_class($job),
            'attempts' => $job->attempts,
            'error_message' => $exception->getMessage(),
            'error_class' => get_class($exception),
            'stack_trace' => $exception->getTraceAsString(),
            'diagnostics' => $this->diagnose($exception),
            'metadata' => $metadata,
            'failed_at' => microtime(true),
            'healed' => false,
        ];
    }

    public function diagnose(\Throwable $exception): array
    {
        $msg = strtolower($exception->getMessage());
        $category = 'UNKNOWN';
        $suggestedAction = 'Inspect error log and verify environment dependencies.';

        if (str_contains($msg, 'timeout') || str_contains($msg, 'timed out')) {
            $category = 'TIMEOUT';
            $suggestedAction = 'Increase execution timeout threshold or scale async fiber pool.';
        } elseif (str_contains($msg, 'connection') || str_contains($msg, 'refused') || str_contains($msg, 'network')) {
            $category = 'NETWORK_TRANSIENT';
            $suggestedAction = 'Apply exponential backoff and retry with circuit breaker protection.';
        } elseif (str_contains($msg, 'memory') || str_contains($msg, 'exhausted')) {
            $category = 'RESOURCE_EXHAUSTION';
            $suggestedAction = 'Chunk data payloads into smaller stream batches.';
        } elseif (str_contains($msg, 'integrity') || str_contains($msg, 'hmac') || str_contains($msg, 'signature')) {
            $category = 'SECURITY_HMAC_MISMATCH';
            $suggestedAction = 'Re-sync component state cryptographic checksums.';
        }

        return [
            'category' => $category,
            'suggested_action' => $suggestedAction,
            'severity' => ($category === 'NETWORK_TRANSIENT') ? 'medium' : 'high',
        ];
    }

    public function getFailedJobs(): array
    {
        return $this->failedJobs;
    }

    public function count(): int
    {
        return count($this->failedJobs);
    }

    public function markHealed(string $dlqId): bool
    {
        foreach ($this->failedJobs as &$entry) {
            if ($entry['id'] === $dlqId) {
                $entry['healed'] = true;
                $entry['healed_at'] = microtime(true);
                return true;
            }
        }
        return false;
    }

    public function clear(): void
    {
        $this->failedJobs = [];
    }
}
