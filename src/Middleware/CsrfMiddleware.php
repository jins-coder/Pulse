<?php

declare(strict_types=1);

namespace Pulse\Middleware;

use Pulse\Http\Request;
use Pulse\Http\Response;

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
