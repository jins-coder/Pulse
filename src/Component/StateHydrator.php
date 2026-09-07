<?php

declare(strict_types=1);

namespace Pulse\Component;

class StateHydrator
{
    private static string $secretKey = 'pulse_framework_secure_hmac_secret_key';

    public static function setSecretKey(string $key): void
    {
        self::$secretKey = $key;
    }

    public static function createSnapshot(string $class, string $id, array $state): string
    {
        $payload = [
            'class' => $class,
            'id' => $id,
            'state' => $state,
            't' => microtime(true),
        ];

        return base64_encode(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    public static function generateChecksum(string $snapshot): string
    {
        return hash_hmac('sha256', $snapshot, self::$secretKey);
    }

    public static function verifyAndHydrate(string $snapshot, string $checksum): Component
    {
        $expectedChecksum = self::generateChecksum($snapshot);
        if (!hash_equals($expectedChecksum, $checksum)) {
            throw new \RuntimeException("Invalid component checksum. State tampering prevented.");
        }

        $decoded = json_decode(base64_decode($snapshot), true);
        if (!$decoded || !isset($decoded['class'], $decoded['id'], $decoded['state'])) {
            throw new \InvalidArgumentException("Malformed component snapshot.");
        }

        $class = $decoded['class'];
        if (!class_exists($class) || !is_subclass_of($class, Component::class)) {
            throw new \InvalidArgumentException("Component class {$class} does not exist or is invalid.");
        }

        /** @var Component $component */
        $component = new $class($decoded['id']);
        $component->setPublicState($decoded['state']);

        return $component;
    }
}
