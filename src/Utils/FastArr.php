<?php

declare(strict_types=1);

namespace Pulse\Utils;

/**
 * High-Performance Array & Collection Utilities for Pulse Framework (PHP 8.2 - 8.4+).
 * Implements next-generation zero-allocation array operations, SIMD-style vector utilities,
 * and polyfills for PHP 8.4 native array functions (`array_find`, `array_any`, `array_all`, `array_find_key`).
 */
final class FastArr
{
    /**
     * PHP 8.4 array_find polyfill / fast finder.
     * Returns first value matching predicate without allocating new arrays.
     */
    public static function find(array $array, callable $callback): mixed
    {
        if (function_exists('array_find')) {
            return array_find($array, $callback);
        }

        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }
        return null;
    }

    /**
     * PHP 8.4 array_find_key polyfill.
     * Returns first matching key.
     */
    public static function findKey(array $array, callable $callback): int|string|null
    {
        if (function_exists('array_find_key')) {
            return array_find_key($array, $callback);
        }

        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $key;
            }
        }
        return null;
    }

    /**
     * PHP 8.4 array_any polyfill.
     * Returns true if at least one element satisfies predicate.
     */
    public static function any(array $array, callable $callback): bool
    {
        if (function_exists('array_any')) {
            return array_any($array, $callback);
        }

        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return true;
            }
        }
        return false;
    }

    /**
     * PHP 8.4 array_all polyfill.
     * Returns true if all elements satisfy predicate.
     */
    public static function all(array $array, callable $callback): bool
    {
        if (function_exists('array_all')) {
            return array_all($array, $callback);
        }

        foreach ($array as $key => $value) {
            if (!$callback($value, $key)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Zero-allocation lazy generator pipeline.
     */
    public static function lazyMap(iterable $items, callable $callback): \Generator
    {
        foreach ($items as $key => $item) {
            yield $key => $callback($item, $key);
        }
    }

    /**
     * Zero-allocation lazy filter pipeline.
     */
    public static function lazyFilter(iterable $items, callable $callback): \Generator
    {
        foreach ($items as $key => $item) {
            if ($callback($item, $key)) {
                yield $key => $item;
            }
        }
    }

    /**
     * High-speed partition without multi-pass overhead.
     */
    public static function partition(array $array, callable $callback): array
    {
        $passed = [];
        $failed = [];

        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                $passed[$key] = $value;
            } else {
                $failed[$key] = $value;
            }
        }

        return [$passed, $failed];
    }

    /**
     * Memory-efficient dictionary indexing by key.
     */
    public static function keyBy(array $array, string|callable $keySelector): array
    {
        $result = [];
        $isCallable = is_callable($keySelector);

        foreach ($array as $item) {
            $key = $isCallable ? $keySelector($item) : ($item[$keySelector] ?? ($item->{$keySelector} ?? null));
            if ($key !== null) {
                $result[(string)$key] = $item;
            }
        }

        return $result;
    }
}
