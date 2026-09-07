<?php

declare(strict_types=1);

namespace Pulse\Middleware;

use Pulse\Http\Request;
use Pulse\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, callable $next): Response;
}
