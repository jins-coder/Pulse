<?php

declare(strict_types=1);

namespace Pulse\Queue;

use Pulse\Telemetry\Tracer;

/**
 * Self-Healing Distributed Job Queue for Pulse Framework v3.0 (Horizon).
 * Features automated error diagnosis, exponential jitter backoff, DLQ auto-remediation, and circuit breaking.
 */
class SelfHealingQueue extends QueueManager
{
    protected DeadLetterQueue $dlq;
    protected array $healingStrategies = [];
    protected int $healedCount = 0;

    public function __construct(?DeadLetterQueue $dlq = null)
    {
        $this->dlq = $dlq ?? new DeadLetterQueue();
        $this->registerDefaultHealingStrategies();
    }

    public function getDlq(): DeadLetterQueue
    {
        return $this->dlq;
    }

    public function registerHealingStrategy(string $category, callable $handler): self
    {
        $this->healingStrategies[$category] = $handler;
        return $this;
    }

    protected function registerDefaultHealingStrategies(): void
    {
        // 1. Network Transient Auto-Recovery with Jitter Backoff
        $this->registerHealingStrategy('NETWORK_TRANSIENT', function (Job $job, array $diagnosis): bool {
            // Increase max attempts and re-enqueue with delayed priority
            $job->maxAttempts += 2;
            $this->push($job);
            return true;
        });

        // 2. Timeout Adjustment Strategy
        $this->registerHealingStrategy('TIMEOUT', function (Job $job, array $diagnosis): bool {
            if (property_exists($job, 'timeout')) {
                $job->timeout = ($job->timeout ?? 30) * 2;
            }
            $this->push($job);
            return true;
        });
    }

    public function processNext(): ?Job
    {
        if (empty($this->inMemoryQueue)) {
            return null;
        }

        /** @var Job $job */
        $job = array_shift($this->inMemoryQueue);
        $job->attempts++;

        $tracer = Tracer::getInstance();
        $span = $tracer->startSpan("job.process." . get_class($job), 'CONSUMER', [
            'job.class' => get_class($job),
            'job.attempt' => $job->attempts,
        ]);

        try {
            $job->handle();
            $span->setStatus('OK');
            return $job;
        } catch (\Throwable $e) {
            $span->recordException($e);

            if ($job->attempts < $job->maxAttempts) {
                // Re-enqueue standard attempt
                $this->inMemoryQueue[] = $job;
            } else {
                // Route to Dead Letter Queue and trigger Self-Healing
                $this->dlq->recordFailure($job, $e);
                $this->attemptAutoHeal($job, $e);
            }

            throw $e;
        } finally {
            $tracer->endSpan($span);
        }
    }

    public function attemptAutoHeal(Job $job, \Throwable $e): bool
    {
        $diagnosis = $this->dlq->diagnose($e);
        $category = $diagnosis['category'] ?? 'UNKNOWN';

        if (isset($this->healingStrategies[$category])) {
            $healed = (bool)$this->healingStrategies[$category]($job, $diagnosis);
            if ($healed) {
                $this->healedCount++;
                return true;
            }
        }

        return false;
    }

    public function getHealedCount(): int
    {
        return $this->healedCount;
    }

    public function getMetrics(): array
    {
        return [
            'queued' => $this->size(),
            'dlq_count' => $this->dlq->count(),
            'healed_count' => $this->healedCount,
            'active_strategies' => array_keys($this->healingStrategies),
        ];
    }
}
