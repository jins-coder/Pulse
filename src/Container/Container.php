<?php

declare(strict_types=1);

namespace Pulse\Container;

use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

class Container
{
    private static ?Container $instance = null;
    protected array $bindings = [];
    protected array $instances = [];

    public static function getInstance(): Container
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function setInstance(?Container $container): void
    {
        self::$instance = $container;
    }

    public function bind(string $abstract, mixed $concrete = null, bool $shared = false): void
    {
        $concrete = $concrete ?? $abstract;
        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared' => $shared,
        ];
    }

    public function singleton(string $abstract, mixed $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }

    public function instance(string $abstract, mixed $instance): void
    {
        $this->instances[$abstract] = $instance;
    }

    public function get(string $id): mixed
    {
        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || isset($this->instances[$id]) || class_exists($id);
    }

    public function resolve(string $abstract, array $parameters = []): mixed
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = $this->bindings[$abstract]['concrete'] ?? $abstract;

        if ($concrete instanceof \Closure) {
            $object = $concrete($this, $parameters);
        } elseif (is_string($concrete) && class_exists($concrete)) {
            $object = $this->build($concrete, $parameters);
        } else {
            $object = $concrete;
        }

        if (!empty($this->bindings[$abstract]['shared'])) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    public function build(string $concrete, array $parameters = []): object
    {
        $reflector = new ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) {
            throw new \RuntimeException("Target [{$concrete}] is not instantiable.");
        }

        $constructor = $reflector->getConstructor();
        if ($constructor === null) {
            return new $concrete();
        }

        $dependencies = $this->resolveDependencies($constructor->getParameters(), $parameters);
        return $reflector->newInstanceArgs($dependencies);
    }

    public function call(callable|array|string $callback, array $parameters = []): mixed
    {
        if (is_array($callback)) {
            [$class, $method] = $callback;
            $instance = is_object($class) ? $class : $this->resolve($class);
            $refMethod = new ReflectionMethod($instance, $method);
            $dependencies = $this->resolveDependencies($refMethod->getParameters(), $parameters);
            return $refMethod->invokeArgs($instance, $dependencies);
        }

        if (is_string($callback) && str_contains($callback, '@')) {
            [$class, $method] = explode('@', $callback);
            return $this->call([$this->resolve($class), $method], $parameters);
        }

        if (is_callable($callback)) {
            $refFunc = new \ReflectionFunction(\Closure::fromCallable($callback));
            $dependencies = $this->resolveDependencies($refFunc->getParameters(), $parameters);
            return $callback(...$dependencies);
        }

        throw new \InvalidArgumentException("Invalid callback provided to Container::call.");
    }

    protected function resolveDependencies(array $parameters, array $explicitParams = []): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();

            if (array_key_exists($name, $explicitParams)) {
                $dependencies[] = $explicitParams[$name];
                continue;
            }

            $type = $parameter->getType();
            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $dependencies[] = $this->resolve($type->getName());
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                throw new \RuntimeException("Cannot resolve un-typed parameter \${$name} with no default value.");
            }
        }

        return $dependencies;
    }
}
