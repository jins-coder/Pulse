<?php

declare(strict_types=1);

namespace Pulse\Async;

use Fiber;

if (!function_exists('Pulse\Async\async')) {
    function async(callable $callback): Future
    {
        $future = new Future($callback);
        FiberLoop::add($future);
        return $future;
    }
}

if (!function_exists('Pulse\Async\await')) {
    function await(mixed $target): mixed
    {
        if ($target instanceof Future) {
            while (!$target->isCompleted()) {
                if (Fiber::getCurrent()) {
                    Fiber::suspend();
                } else {
                    FiberLoop::run();
                }
            }
            return $target->getResult();
        }

        if (is_callable($target)) {
            $future = async($target);
            return await($future);
        }

        return $target;
    }
}

if (!function_exists('Pulse\Async\all')) {
    function all(array $tasks): array
    {
        $futures = [];
        foreach ($tasks as $key => $task) {
            $futures[$key] = is_callable($task) ? async($task) : $task;
        }

        $allDone = false;
        while (!$allDone) {
            $allDone = true;
            foreach ($futures as $key => $future) {
                if ($future instanceof Future && !$future->isCompleted()) {
                    $allDone = false;
                }
            }

            if (!$allDone) {
                if (Fiber::getCurrent()) {
                    Fiber::suspend();
                } else {
                    usleep(1000);
                }
            }
        }

        $results = [];
        foreach ($futures as $key => $future) {
            $results[$key] = $future instanceof Future ? $future->getResult() : $future;
        }

        return $results;
    }
}

if (!function_exists('Pulse\Async\delay')) {
    function delay(int $milliseconds): void
    {
        $start = microtime(true);
        $seconds = $milliseconds / 1000.0;

        while ((microtime(true) - $start) < $seconds) {
            if (Fiber::getCurrent()) {
                Fiber::suspend();
            } else {
                usleep(1000);
            }
        }
    }
}
