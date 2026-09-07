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

// Observability & Request Replay Studio
$app->router->get('/_pulse/replay', function () {
    return [
        'studio' => 'Pulse Observability & Request Replay Studio',
        'version' => \Pulse\Pulse::VERSION,
        'recent_snapshots' => [
            [
                'id' => 'req_demo_01',
                'timestamp' => date('c'),
                'uri' => '/dashboard',
                'method' => 'GET',
                'status' => '200 OK',
                'duration_ms' => 1.42,
            ],
            [
                'id' => 'req_demo_02',
                'timestamp' => date('c', time() - 300),
                'uri' => '/_pulse/action',
                'method' => 'POST',
                'action' => 'setPeriod',
                'component' => 'App\\Components\\AnalyticsWidget',
                'status' => '200 OK',
                'duration_ms' => 0.85,
            ]
        ],
        'features' => [
            'sanitized_snapshots' => true,
            'one_click_local_replay' => true,
            'query_flamegraphs' => true,
        ]
    ];
})->name('pulse.replay');

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
