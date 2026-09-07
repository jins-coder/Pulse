<?php

declare(strict_types=1);

namespace Pulse\Replay;

use Pulse\Http\Request;

class RequestReplay
{
    protected static array $redactedKeys = ['password', 'secret', 'token', 'authorization', 'api_key'];

    public static function capture(Request $request, ?\Throwable $exception = null): string
    {
        $snapshot = [
            'id' => 'req_' . bin2hex(random_bytes(6)),
            'timestamp' => date('c'),
            'method' => $request->method,
            'uri' => $request->uri,
            'headers' => self::sanitize($request->headers),
            'query' => $request->query,
            'post' => self::sanitize($request->post),
            'json' => self::sanitize($request->json ?? []),
            'exception' => $exception ? [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ] : null,
        ];

        return json_encode($snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public static function createReplayRequest(string $snapshotJson): Request
    {
        $data = json_decode($snapshotJson, true);
        return new Request(
            method: $data['method'],
            uri: $data['uri'],
            headers: $data['headers'] ?? [],
            query: $data['query'] ?? [],
            post: $data['post'] ?? [],
            files: [],
            server: ['REQUEST_METHOD' => $data['method'], 'REQUEST_URI' => $data['uri']],
            rawBody: !empty($data['json']) ? json_encode($data['json']) : null,
            json: $data['json'] ?? null
        );
    }

    protected static function sanitize(array $data): array
    {
        $clean = [];
        foreach ($data as $key => $value) {
            if (in_array(strtolower((string)$key), self::$redactedKeys)) {
                $clean[$key] = '***REDACTED***';
            } elseif (is_array($value)) {
                $clean[$key] = self::sanitize($value);
            } else {
                $clean[$key] = $value;
            }
        }
        return $clean;
    }
}
