<?php

declare(strict_types=1);

namespace Pulse\Async;

use Fiber;
use Throwable;

class Future
{
    protected ?Fiber $fiber = null;
    protected mixed $result = null;
    protected ?Throwable $exception = null;
    protected bool $completed = false;

    public function __construct(callable $callback)
    {
        $this->fiber = new Fiber(function () use ($callback) {
            try {
                $this->result = $callback();
            } catch (Throwable $e) {
                $this->exception = $e;
            } finally {
                $this->completed = true;
            }
        });
    }

    public function start(): void
    {
        if ($this->fiber && !$this->fiber->isStarted()) {
            $this->fiber->start();
        }
    }

    public function resume(mixed $value = null): void
    {
        if ($this->fiber && $this->fiber->isSuspended()) {
            $this->fiber->resume($value);
        }
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function getResult(): mixed
    {
        if ($this->exception) {
            throw $this->exception;
        }
        return $this->result;
    }

    public function getFiber(): ?Fiber
    {
        return $this->fiber;
    }
}

class FiberLoop
{
    /** @var Future[] */
    protected static array $futures = [];

    public static function add(Future $future): void
    {
        self::$futures[] = $future;
        $future->start();
    }

    public static function run(): void
    {
        while (!empty(self::$futures)) {
            foreach (self::$futures as $index => $future) {
                if ($future->isCompleted()) {
                    unset(self::$futures[$index]);
                    continue;
                }

                if ($future->getFiber()->isSuspended()) {
                    $future->resume();
                }
            }
            usleep(1000);
        }
    }
}
