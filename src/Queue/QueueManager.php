<?php

declare(strict_types=1);

namespace Pulse\Queue;

abstract class Job
{
    public int $attempts = 0;
    public int $maxAttempts = 3;

    abstract public function handle(): void;
}

class QueueManager
{
    protected array $inMemoryQueue = [];

    public function push(Job $job): void
    {
        $this->inMemoryQueue[] = $job;
    }

    public function processNext(): ?Job
    {
        if (empty($this->inMemoryQueue)) {
            return null;
        }

        /** @var Job $job */
        $job = array_shift($this->inMemoryQueue);
        $job->attempts++;

        try {
            $job->handle();
            return $job;
        } catch (\Throwable $e) {
            if ($job->attempts < $job->maxAttempts) {
                $this->inMemoryQueue[] = $job;
            }
            throw $e;
        }
    }

    public function size(): int
    {
        return count($this->inMemoryQueue);
    }
}
