<?php

declare(strict_types=1);

namespace Pulse\Telemetry;

/**
 * OpenTelemetry Distributed Tracer for Pulse Framework.
 * Manages active trace contexts, nested span hierarchies, and exporter storage.
 */
class Tracer
{
    private static ?Tracer $instance = null;
    protected ?string $currentTraceId = null;
    protected array $activeSpanStack = [];
    protected array $completedSpans = [];
    protected int $maxCompletedSpans = 200;

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function getInstance(): Tracer
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function startTrace(?string $traceId = null): string
    {
        $this->currentTraceId = $traceId ?? $this->generateHex(16);
        $this->activeSpanStack = [];
        return $this->currentTraceId;
    }

    public function getTraceId(): string
    {
        if ($this->currentTraceId === null) {
            $this->startTrace();
        }
        return $this->currentTraceId;
    }

    public function startSpan(string $name, string $kind = 'INTERNAL', array $attributes = []): Span
    {
        $traceId = $this->getTraceId();
        $spanId = $this->generateHex(8);
        $parentSpan = $this->getCurrentSpan();
        $parentSpanId = $parentSpan?->spanId;

        $span = new Span(
            name: $name,
            traceId: $traceId,
            spanId: $spanId,
            parentSpanId: $parentSpanId,
            kind: $kind
        );

        if (!empty($attributes)) {
            $span->setAttributes($attributes);
        }

        $this->activeSpanStack[] = $span;
        return $span;
    }

    public function getCurrentSpan(): ?Span
    {
        $count = count($this->activeSpanStack);
        return $count > 0 ? $this->activeSpanStack[$count - 1] : null;
    }

    public function endSpan(?Span $span = null): void
    {
        if ($span === null) {
            $span = array_pop($this->activeSpanStack);
        } else {
            $index = array_search($span, $this->activeSpanStack, true);
            if ($index !== false) {
                array_splice($this->activeSpanStack, $index, 1);
            }
        }

        if ($span !== null) {
            if ($span->endTime === null) {
                $span->end();
            }
            $this->completedSpans[] = $span;
            if (count($this->completedSpans) > $this->maxCompletedSpans) {
                array_shift($this->completedSpans);
            }
        }
    }

    /**
     * Execute a callable within a dedicated trace span.
     */
    public function trace(string $name, callable $callback, string $kind = 'INTERNAL', array $attributes = []): mixed
    {
        $span = $this->startSpan($name, $kind, $attributes);
        try {
            $result = $callback($span);
            $span->setStatus('OK');
            return $result;
        } catch (\Throwable $e) {
            $span->recordException($e);
            throw $e;
        } finally {
            $this->endSpan($span);
        }
    }

    public function getCompletedSpans(): array
    {
        return array_map(fn(Span $s) => $s->toArray(), $this->completedSpans);
    }

    public function getSpansForTrace(string $traceId): array
    {
        $spans = array_filter($this->completedSpans, fn(Span $s) => $s->traceId === $traceId);
        return array_map(fn(Span $s) => $s->toArray(), array_values($spans));
    }

    public function clear(): void
    {
        $this->completedSpans = [];
        $this->activeSpanStack = [];
        $this->currentTraceId = null;
    }

    private function generateHex(int $bytes): string
    {
        return bin2hex(random_bytes($bytes));
    }
}
