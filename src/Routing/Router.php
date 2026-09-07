<?php

declare(strict_types=1);

namespace Pulse\Routing;

use Pulse\Http\Request;
use Pulse\Http\Response;

class Router
{
    /** @var Route[] */
    protected array $routes = [];
    protected array $groupStack = [];

    public function get(string $path, mixed $handler): Route
    {
        return $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, mixed $handler): Route
    {
        return $this->addRoute('POST', $path, $handler);
    }

    public function any(string $path, mixed $handler): Route
    {
        return $this->addRoute('ANY', $path, $handler);
    }

    public function addRoute(string $method, string $path, mixed $handler): Route
    {
        $prefix = $this->getCurrentGroupPrefix();
        $fullPath = '/' . trim($prefix . '/' . trim($path, '/'), '/');
        if ($fullPath !== '/') {
            $fullPath = rtrim($fullPath, '/');
        }

        $route = new Route($method, $fullPath, $handler);
        
        $groupMiddleware = $this->getCurrentGroupMiddleware();
        if (!empty($groupMiddleware)) {
            $route->middleware($groupMiddleware);
        }

        $this->routes[] = $route;
        return $route;
    }

    public function group(array $attributes, callable $callback): void
    {
        $this->groupStack[] = $attributes;
        $callback($this);
        array_pop($this->groupStack);
    }

    protected function getCurrentGroupPrefix(): string
    {
        $prefixes = [];
        foreach ($this->groupStack as $group) {
            if (isset($group['prefix'])) {
                $prefixes[] = trim($group['prefix'], '/');
            }
        }
        return implode('/', $prefixes);
    }

    protected function getCurrentGroupMiddleware(): array
    {
        $middlewares = [];
        foreach ($this->groupStack as $group) {
            if (isset($group['middleware'])) {
                $middlewares = array_merge($middlewares, (array)$group['middleware']);
            }
        }
        return $middlewares;
    }

    public function match(Request $request): ?Route
    {
        $method = $request->method;
        $path = $request->uri;

        foreach ($this->routes as $route) {
            if ($route->matches($method, $path)) {
                return $route;
            }
        }

        return null;
    }

    public function url(string $name, array $params = []): string
    {
        foreach ($this->routes as $route) {
            if ($route->name === $name) {
                $url = $route->pattern;
                foreach ($params as $k => $v) {
                    $url = str_replace('{' . $k . '}', (string)$v, $url);
                }
                return $url;
            }
        }
        return '/';
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
