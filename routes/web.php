<?php

declare(strict_types=1);

use Pulse\Pulse;
use Pulse\Http\Request;

/** @var Pulse $app */

// Web Routes (Tri-Mode: SSR on initial visit, SPA on internal navigation, JSON for API)
$app->router->get('/', function () {
    return view('pages.home', [
        'title' => 'Pulse • Modern Reactive PHP Framework',
    ]);
})->name('home');

$app->router->get('/about', function () {
    return view('pages.about', [
        'title' => 'Architecture & Subsystems • Pulse PHP',
    ]);
})->name('about');

// API Info Endpoint
$app->router->get('/api/info', function () {
    return [
        'framework' => 'Pulse PHP Application Framework',
        'version' => '1.0.0',
        'architecture' => [
            'tri_mode_routing' => 'SSR ↔ SPA ↔ API',
            'reactive_components' => 'Native PHP with HMAC Integrity',
            'client_runtime' => 'Zero-Build Pulse.js (<10KB)',
            'di_container' => 'PSR-11 Auto-wiring',
            'multi_tenancy' => 'Built-in Tenant Scoping',
            'async_concurrency' => 'PHP 8.1+ Fibers (await, all)',
            'realtime' => 'WebSockets & SSE Channels',
            'persistent_runtime' => 'Fast Boot Worker Loop',
            'profiler' => 'Embedded Microsecond Profiler Toolbar',
        ],
        'status' => 'operational',
        'timestamp' => time(),
    ];
})->name('api.info');
