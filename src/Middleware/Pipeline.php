<?php

declare(strict_types=1);

namespace Pulse\Middleware;

use Pulse\Http\Request;
use Pulse\Http\Response;
use Pulse\Container\Container;

interface MiddlewareInterface
{
    public function process(Request $request, callable $next): Response;
}

class Pipeline
{
    protected array $pipes = [];
    protected Request $request;

    public function send(Request $request): self
    {
        $this->request = $request;
        return $this;
    }

    public function through(array $pipes): self
    {
        $this->pipes = $pipes;
        return $this;
    }

    public function then(callable $destination): Response
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes),
            function ($next, $pipe) {
                return function (Request $request) use ($next, $pipe) {
                    if (is_string($pipe)) {
                        $pipe = Container::getInstance()->resolve($pipe);
                    }

                    if ($pipe instanceof MiddlewareInterface) {
                        return $pipe->process($request, $next);
                    }

                    if (is_callable($pipe)) {
                        return $pipe($request, $next);
                    }

                    throw new \InvalidArgumentException("Invalid middleware in Pulse Pipeline.");
                };
            },
            $destination
        );

        return $pipeline($this->request);
    }
}

class CsrfMiddleware implements MiddlewareInterface
{
    private static ?string $token = null;

    public static function getToken(): string
    {
        if (self::$token === null) {
            if (session_status() === PHP_SESSION_NONE) {
                @session_start();
            }
            if (empty($_SESSION['_pulse_csrf'])) {
                $_SESSION['_pulse_csrf'] = bin2hex(random_bytes(32));
            }
            self::$token = $_SESSION['_pulse_csrf'];
        }
        return self::$token;
    }

    public function process(Request $request, callable $next): Response
    {
        if (in_array($request->method, ['GET', 'HEAD', 'OPTIONS']) || $request->isReactiveAction()) {
            return $next($request);
        }

        $token = $request->header('x-csrf-token') 
            ?? $request->input('_token') 
            ?? ($request->json['_token'] ?? null);

        if (!$token || !hash_equals(self::getToken(), $token)) {
            return Response::json(['error' => 'CSRF verification failed.'], 419);
        }

        return $next($request);
    }
}

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

class RateLimiterMiddleware implements MiddlewareInterface
{
    protected static array $hits = [];

    public function __construct(
        protected int $maxAttempts = 60,
        protected int $decaySeconds = 60
    ) {}

    public function process(Request $request, callable $next): Response
    {
        $ip = $request->server['REMOTE_ADDR'] ?? '127.0.0.1';
        $key = md5($ip . ':' . $request->uri);
        $now = time();

        self::$hits[$key] = array_filter(
            self::$hits[$key] ?? [],
            fn($t) => $t > ($now - $this->decaySeconds)
        );

        if (count(self::$hits[$key]) >= $this->maxAttempts) {
            $retryAfter = $this->decaySeconds - ($now - min(self::$hits[$key]));
            return Response::json([
                'error' => 'Too many requests. Slow down.',
                'retry_after' => max(1, $retryAfter),
            ], 429);
        }

        self::$hits[$key][] = $now;
        return $next($request);
    }
}
