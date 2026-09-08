<?php

declare(strict_types=1);

use Pulse\Pulse;
use Pulse\Queue\QueueManager;

// Unified Global Helper Functions (Laravel-Style)
if (!function_exists('pulse')) {
    function pulse(): Pulse {
        return Pulse::getInstance();
    }
}

if (!function_exists('app')) {
    function app(): Pulse {
        return Pulse::getInstance();
    }
}

if (!function_exists('view')) {
    function view(string $template, array $data = []): string {
        return pulse()->viewEngine->render($template, $data);
    }
}

if (!function_exists('component')) {
    function component(string $class, array $params = []): string {
        return pulse()->viewEngine->component($class, $params);
    }
}

if (!function_exists('route')) {
    function route(string $name, array $params = []): string {
        return pulse()->router->url($name, $params);
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('event')) {
    function event(object $event): object {
        return pulse()->events->dispatch($event);
    }
}

if (!function_exists('queue')) {
    function queue(): QueueManager {
        return pulse()->queue;
    }
}

if (!function_exists('tracer')) {
    function tracer(): \Pulse\Telemetry\Tracer {
        return pulse()->tracer;
    }
}

if (!function_exists('agents')) {
    function agents(): \Pulse\AI\AgentMesh {
        return pulse()->agents;
    }
}

if (!function_exists('crdt')) {
    function crdt(): \Pulse\Realtime\CrdtStateSync {
        return pulse()->crdt;
    }
}

if (!function_exists('wasm')) {
    function wasm(): \Pulse\Wasm\WasmRuntime {
        return pulse()->wasm;
    }
}

if (!function_exists('aot')) {
    function aot(): \Pulse\Compiler\AotCompiler {
        return pulse()->aot;
    }
}

if (!function_exists('microvm')) {
    function microvm(): \Pulse\Runtime\MicroVM\MicroVMKernel {
        return pulse()->microvm;
    }
}

if (!function_exists('fast_arr')) {
    /**
     * Fluent high-performance array / collection utility
     *
     * @param iterable<mixed> $items
     * @return \Pulse\Utils\FastArr
     */
    function fast_arr(iterable $items = []): \Pulse\Utils\FastArr {
        return new \Pulse\Utils\FastArr($items);
    }
}

if (!function_exists('array_find')) {
    /**
     * PHP 8.4 Polyfill: Returns the value of the first element satisfying the predicate
     */
    function array_find(array $array, callable $callback): mixed {
        return \Pulse\Utils\FastArr::find($array, $callback);
    }
}

if (!function_exists('array_find_key')) {
    /**
     * PHP 8.4 Polyfill: Returns the key of the first element satisfying the predicate
     */
    function array_find_key(array $array, callable $callback): mixed {
        return \Pulse\Utils\FastArr::findKey($array, $callback);
    }
}

if (!function_exists('array_any')) {
    /**
     * PHP 8.4 Polyfill: Returns true if at least one element satisfies the predicate
     */
    function array_any(array $array, callable $callback): bool {
        return \Pulse\Utils\FastArr::any($array, $callback);
    }
}

if (!function_exists('array_all')) {
    /**
     * PHP 8.4 Polyfill: Returns true if all elements satisfy the predicate
     */
    function array_all(array $array, callable $callback): bool {
        return \Pulse\Utils\FastArr::all($array, $callback);
    }
}

