<?php

declare(strict_types=1);

// Pulse Framework Bootstrap Entry
$baseDir = dirname(__DIR__);

// Auto-loader
spl_autoload_register(function ($class) use ($baseDir) {
    if (str_starts_with($class, 'Pulse\\')) {
        $file = $baseDir . '/src/' . str_replace('\\', '/', substr($class, 6)) . '.php';
        if (file_exists($file)) require_once $file;
    } elseif (str_starts_with($class, 'App\\')) {
        $file = $baseDir . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (file_exists($file)) require_once $file;
    }
});

require_once $baseDir . '/src/Async/functions.php';

use Pulse\Pulse;
use Pulse\Http\Request;

$app = new Pulse($baseDir);

// Load Routes
require_once $baseDir . '/routes/web.php';

// Handle Request
$request = Request::capture();
$response = $app->handle($request);
$response->send();
