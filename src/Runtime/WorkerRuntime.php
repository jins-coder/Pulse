<?php

declare(strict_types=1);

namespace Pulse\Runtime;

use Pulse\Http\Request;
use Pulse\Http\Response;

class WorkerRuntime
{
    protected int $requestCount = 0;
    protected float $startTime;

    public function __construct(
        protected mixed $appFactory,
        protected int $maxRequests = 1000
    ) {
        $this->startTime = microtime(true);
    }

    public function run(callable $requestReceiver, callable $responseSender): void
    {
        $app = ($this->appFactory)();
        echo "[Pulse Worker] Booted in " . round((microtime(true) - $this->startTime) * 1000, 2) . "ms\n";

        while ($this->requestCount < $this->maxRequests) {
            $rawRequest = $requestReceiver();
            if ($rawRequest === null) {
                break;
            }

            $this->requestCount++;
            $response = $app->handle($rawRequest);
            $responseSender($response);

            gc_collect_cycles();
        }

        echo "[Pulse Worker] Worker recycled after {$this->requestCount} requests.\n";
    }
}
