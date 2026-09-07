<?php

declare(strict_types=1);

namespace Pulse\Routing;

use Pulse\Http\Request;
use Pulse\Http\Response;

class Route
{
    public array $parameters = [];
    public ?string $name = null;
    public array $wheres = [];
    public array $middleware = [];

    public function __construct(
        public readonly string $method,
        public readonly string $pattern,
        public readonly mixed $handler
    ) {}

    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function middleware(array|string $middleware): self
    {
        $this->middleware = array_merge($this->middleware, (array)$middleware);
        return $this;
    }

    public function whereNumber(string $param): self
    {
        $this->wheres[$param] = '[0-9]+';
        return $this;
    }

    public function where(string $param, string $regex): self
    {
        $this->wheres[$param] = $regex;
        return $this;
    }

    public function matches(string $method, string $path): bool
    {
        if ($this->method !== 'ANY' && $this->method !== $method) {
            return false;
        }

        $regex = $this->compilePattern();
        if (preg_match($regex, $path, $matches)) {
            $params = [];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            $this->parameters = $params;
            return true;
        }

        return false;
    }

    protected function compilePattern(): string
    {
        $pattern = preg_replace_callback('/\{([a-zA-Z0-9_]+)\}/', function ($matches) {
            $param = $matches[1];
            $constraint = $this->wheres[$param] ?? '[^/]+';
            return "(?P<{$param}>{$constraint})";
        }, $this->pattern);

        return '#^' . $pattern . '$#';
    }
}
