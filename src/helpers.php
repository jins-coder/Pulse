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
