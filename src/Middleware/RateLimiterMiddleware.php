<?php

declare(strict_types=1);

namespace Pulse\Middleware;

use Pulse\Http\Request;
use Pulse\Http\Response;

class RateLimiterMiddleware implements MiddlewareInterface
{
    protected static array $hits = [];

    public function __construct(
        protected int $maxAttempts = 60,
        protected int $decaySeconds = 60
    ) {}

    public function process(Request $request, callable $next): Response
    {
        $key = md5($request->ip . ':' . $request->uri);
        $now = time();

        self::$hits[$key] = array_filter(
            self::$hits[$key] ?? [],
            fn($timestamp) => ($now - $timestamp) < $this->decaySeconds
        );

        if (count(self::$hits[$key]) >= $this->maxAttempts) {
            return Response::json([
                'error' => 'Too Many Requests',
                'retry_after' => $this->decaySeconds,
            ], 429);
        }

        self::$hits[$key][] = $now;
        return $next($request);
    }
}
