<?php

declare(strict_types=1);

namespace Pulse\Streaming;

class StreamingResponse
{
    public function __construct(
        protected \Closure $streamCallback
    ) {}

    public function send(): void
    {
        if (!headers_sent()) {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');
        }

        while (ob_get_level()) {
            ob_end_flush();
        }
        flush();

        $writer = function (string $event, mixed $data) {
            echo "event: {$event}\n";
            echo "data: " . json_encode($data) . "\n\n";
            flush();
        };

        ($this->streamCallback)($writer);
    }
}
