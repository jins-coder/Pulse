<?php

declare(strict_types=1);

namespace Pulse\Http;

class Request
{
    public function __construct(
        public readonly string $method,
        public readonly string $uri,
        public readonly array $headers,
        public readonly array $query,
        public readonly array $post,
        public readonly array $files,
        public readonly array $server,
        public readonly ?string $rawBody = null,
        public readonly ?array $json = null
    ) {}

    public static function capture(): self
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headerName = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$headerName] = $value;
            } elseif (in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'AUTH_TYPE'])) {
                $headerName = strtolower(str_replace('_', '-', $key));
                $headers[$headerName] = $value;
            }
        }

        $rawBody = file_get_contents('php://input') ?: null;
        $json = null;
        if ($rawBody && str_contains($headers['content-type'] ?? '', 'application/json')) {
            $json = json_decode($rawBody, true);
        }

        return new self(
            method: $method,
            uri: $uri,
            headers: $headers,
            query: $_GET,
            post: $_POST,
            files: $_FILES,
            server: $_SERVER,
            rawBody: $rawBody,
            json: $json
        );
    }

    public function isSpa(): bool
    {
        return ($this->headers['x-requested-mode'] ?? '') === 'spa' 
            || ($this->headers['x-spa'] ?? '') === 'true';
    }

    public function isReactiveAction(): bool
    {
        return ($this->headers['x-reactive-action'] ?? '') === 'true'
            || $this->uri === '/_pulse/action'
            || $this->uri === '/_framework/action';
    }

    public function wantsJson(): bool
    {
        $accept = $this->headers['accept'] ?? '';
        return str_contains($accept, 'application/json') || ($this->headers['x-mode'] ?? '') === 'api';
    }

    public function header(string $key, ?string $default = null): ?string
    {
        return $this->headers[strtolower($key)] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        if ($this->json && array_key_exists($key, $this->json)) {
            return $this->json[$key];
        }
        return $this->post[$key] ?? $this->query[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->post, $this->json ?? []);
    }
}
