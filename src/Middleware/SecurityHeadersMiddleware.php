<?php

declare(strict_types=1);

namespace Pulse\Middleware;

use Pulse\Http\Request;
use Pulse\Http\Response;

class SecurityHeadersMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $next): Response
    {
        $response = $next($request);
        $response->headers['X-Content-Type-Options'] = 'nosniff';
        $response->headers['X-Frame-Options'] = 'SAMEORIGIN';
        $response->headers['X-XSS-Protection'] = '1; mode=block';
        $response->headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
        return $response;
    }
}
