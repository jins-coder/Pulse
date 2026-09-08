<?php

declare(strict_types=1);

namespace Pulse\Telemetry;

/**
 * OpenTelemetry-compatible immutable Span representation.
 */
class Span
{
    public readonly string $traceId;
    public readonly string $spanId;
    public readonly ?string $parentSpanId;
    public readonly string $name;
    public readonly string $kind;
    public readonly float $startTime;
    public ?float $endTime = null;
    public float $durationMs = 0.0;
    public array $attributes = [];
    public array $events = [];
    public string $status = 'UNSET'; // OK, ERROR, UNSET
    public ?string $statusMessage = null;

    public function __construct(
        string $name,
        string $traceId,
        string $spanId,
        ?string $parentSpanId = null,
        string $kind = 'INTERNAL',
        ?float $startTime = null
    ) {
        $this->name = $name;
        $this->traceId = $traceId;
        $this->spanId = $spanId;
        $this->parentSpanId = $parentSpanId;
        $this->kind = $kind;
        $this->startTime = $startTime ?? microtime(true);
    }

    public function setAttribute(string $key, mixed $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function setAttributes(array $attributes): self
    {
        foreach ($attributes as $k => $v) {
            $this->attributes[$k] = $v;
        }
        return $this;
    }

    public function addEvent(string $name, array $attributes = []): self
    {
        $this->events[] = [
            'name' => $name,
            'timestamp' => microtime(true),
            'attributes' => $attributes,
        ];
        return $this;
    }

    public function setStatus(string $status, ?string $message = null): self
    {
        $this->status = strtoupper($status);
        $this->statusMessage = $message;
        return $this;
    }

    public function recordException(\Throwable $exception): self
    {
        $this->setStatus('ERROR', $exception->getMessage());
        $this->addEvent('exception', [
            'exception.type' => get_class($exception),
            'exception.message' => $exception->getMessage(),
            'exception.stacktrace' => $exception->getTraceAsString(),
        ]);
        return $this;
    }

    public function end(?float $endTime = null): void
    {
        $this->endTime = $endTime ?? microtime(true);
        $this->durationMs = round(($this->endTime - $this->startTime) * 1000, 3);
        if ($this->status === 'UNSET') {
            $this->status = 'OK';
        }
    }

    public function toArray(): array
    {
        return [
            'trace_id' => $this->traceId,
            'span_id' => $this->spanId,
            'parent_span_id' => $this->parentSpanId,
            'name' => $this->name,
            'kind' => $this->kind,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'duration_ms' => $this->durationMs,
            'status' => $this->status,
            'status_message' => $this->statusMessage,
            'attributes' => $this->attributes,
            'events' => $this->events,
        ];
    }
}
