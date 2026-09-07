<?php

declare(strict_types=1);

namespace Pulse\Runtime;

use Fiber;
use Pulse\Pulse;
use Pulse\Http\Request;
use Pulse\Http\Response;

class FiberReactor
{
    protected string $host;
    protected int $port;
    protected mixed $socket;
    protected Pulse $app;
    protected string $publicDir;
    protected bool $running = false;
    protected array $connections = [];

    public function __construct(
        string $host = '127.0.0.1',
        int $port = 8000,
        ?string $baseDir = null
    ) {
        $this->host = $host;
        $this->port = $port;
        $baseDir = $baseDir ?: dirname(__DIR__, 2);
        $this->publicDir = $baseDir . '/public';
        
        // Boot application ONCE in memory
        $this->app = new Pulse($baseDir);
        require_once $baseDir . '/routes/web.php';
    }

    public function start(): void
    {
        $address = "tcp://{$this->host}:{$this->port}";
        $this->socket = stream_socket_server($address, $errno, $errstr, STREAM_SERVER_BIND | STREAM_SERVER_LISTEN);

        if (!$this->socket) {
            throw new \RuntimeException("Failed to bind Fiber Reactor to {$address}: [{$errno}] {$errstr}");
        }

        stream_set_blocking($this->socket, false);
        $this->running = true;

        echo "\033[36m⚡ Pulse Fiber Reactor v2.0 (Quantum)\033[0m\n";
        echo "Listening on \033[32mhttp://{$this->host}:{$this->port}\033[0m with pure PHP Fiber event loop\n";
        echo "Press Ctrl+C to stop.\n\n";

        while ($this->running) {
            $read = array_merge([$this->socket], $this->connections);
            $write = null;
            $except = null;

            if (@stream_select($read, $write, $except, 0, 10000) > 0) {
                // New incoming connection
                if (in_array($this->socket, $read)) {
                    $client = @stream_socket_accept($this->socket, 0);
                    if ($client) {
                        stream_set_blocking($client, false);
                        $this->connections[(int)$client] = $client;
                    }
                    $readKey = array_search($this->socket, $read);
                    unset($read[$readKey]);
                }

                // Handle active client sockets inside Fibers
                foreach ($read as $client) {
                    $fiber = new Fiber(function () use ($client) {
                        $this->handleClient($client);
                    });
                    $fiber->start();
                }
            }
        }
    }

    protected function handleClient($client): void
    {
        $rawRequest = '';
        while ($chunk = @fread($client, 8192)) {
            $rawRequest .= $chunk;
            if (str_contains($rawRequest, "\r\n\r\n")) {
                break;
            }
        }

        if (empty($rawRequest)) {
            $this->closeClient($client);
            return;
        }

        // Parse HTTP Request
        $request = $this->parseHttpRequest($rawRequest);

        // Check if static file in public directory exists
        $filePath = realpath($this->publicDir . $request->uri);
        if ($filePath && is_file($filePath) && str_starts_with($filePath, realpath($this->publicDir))) {
            $this->serveStaticFile($client, $filePath);
            $this->closeClient($client);
            return;
        }

        // Route through warmed up Pulse application
        try {
            $response = $this->app->handle($request);
            $this->sendHttpResponse($client, $response);
        } catch (\Throwable $e) {
            $errResponse = Response::html("<h1>500 Internal Server Error</h1><pre>{$e->getMessage()}</pre>", 500);
            $this->sendHttpResponse($client, $errResponse);
        } finally {
            $this->closeClient($client);
            gc_collect_cycles();
        }
    }

    protected function parseHttpRequest(string $raw): Request
    {
        $lines = explode("\r\n", $raw);
        $reqLine = explode(' ', array_shift($lines));
        $method = strtoupper($reqLine[0] ?? 'GET');
        $uri = parse_url($reqLine[1] ?? '/', PHP_URL_PATH) ?: '/';

        $headers = [];
        $body = '';
        $isBody = false;

        foreach ($lines as $line) {
            if ($line === '') {
                $isBody = true;
                continue;
            }
            if ($isBody) {
                $body .= $line . "\r\n";
            } elseif (str_contains($line, ':')) {
                [$k, $v] = explode(':', $line, 2);
                $headers[strtolower(trim($k))] = trim($v);
            }
        }

        $json = null;
        if (str_contains($headers['content-type'] ?? '', 'application/json')) {
            $json = json_decode($body, true);
        }

        return new Request(
            method: $method,
            uri: $uri,
            headers: $headers,
            query: [],
            post: [],
            files: [],
            server: ['REQUEST_METHOD' => $method, 'REQUEST_URI' => $uri],
            rawBody: $body ?: null,
            json: $json
        );
    }

    protected function serveStaticFile($client, string $path): void
    {
        $mime = 'text/plain';
        if (str_ends_with($path, '.js')) $mime = 'application/javascript';
        elseif (str_ends_with($path, '.css')) $mime = 'text/css';
        elseif (str_ends_with($path, '.html')) $mime = 'text/html';
        elseif (str_ends_with($path, '.json')) $mime = 'application/json';
        elseif (str_ends_with($path, '.svg')) $mime = 'image/svg+xml';

        $content = file_get_contents($path);
        $header = "HTTP/1.1 200 OK\r\nContent-Type: {$mime}\r\nContent-Length: " . strlen($content) . "\r\nConnection: close\r\n\r\n";
        @fwrite($client, $header . $content);
    }

    protected function sendHttpResponse($client, Response $response): void
    {
        $headers = "HTTP/1.1 {$response->statusCode} OK\r\n";
        foreach ($response->headers as $k => $v) {
            $headers .= "{$k}: {$v}\r\n";
        }
        $headers .= "Content-Length: " . strlen($response->content) . "\r\n";
        $headers .= "Connection: close\r\n\r\n";

        @fwrite($client, $headers . $response->content);
    }

    protected function closeClient($client): void
    {
        $id = (int)$client;
        unset($this->connections[$id]);
        @fclose($client);
    }
}
