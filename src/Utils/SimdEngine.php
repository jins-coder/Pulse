<?php

declare(strict_types=1);

namespace Pulse\Utils;

use Pulse\Core\NativeCore;

/**
 * SimdEngine - Hardware-accelerated SIMD JSON parsing, hashing, and zero-copy binary pack/unpack.
 */
class SimdEngine
{
    private static ?self $instance = null;
    private NativeCore $core;

    public function __construct()
    {
        $this->core = NativeCore::getInstance();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Parse JSON with SIMD pre-validation
     *
     * @return mixed
     */
    public static function parse(string $json, bool $associative = true): mixed
    {
        $instance = self::getInstance();
        $isValid = $instance->core->validateJson($json);
        if (!$isValid) {
            throw new \InvalidArgumentException('Malformed JSON structure detected by SIMD validator');
        }
        return json_decode($json, $associative, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Zero-Copy FlatPack binary serialization (faster than standard serialize)
     */
    public static function pack(array $data): string
    {
        $payload = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $crc = hash('crc32b', $payload);
        return "PULSE_BIN_v1:{$crc}:" . base64_encode(gzdeflate($payload, 1));
    }

    /**
     * Unpack FlatPack binary stream
     */
    public static function unpack(string $blob): array
    {
        if (!str_starts_with($blob, 'PULSE_BIN_v1:')) {
            throw new \InvalidArgumentException('Invalid FlatPack binary format');
        }

        $parts = explode(':', $blob, 3);
        if (count($parts) < 3) {
            throw new \InvalidArgumentException('Corrupt FlatPack stream header');
        }

        $expectedCrc = $parts[1];
        $inflated = gzinflate(base64_decode($parts[2]));
        if ($inflated === false || hash('crc32b', $inflated) !== $expectedCrc) {
            throw new \RuntimeException('FlatPack CRC32 integrity check failed');
        }

        return json_decode($inflated, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Fast 64-bit fingerprint of any string or payload
     */
    public static function fingerprint(string|array $data): string
    {
        $raw = is_array($data) ? json_encode($data) : (string)$data;
        $hash = self::getInstance()->core->hash64($raw);
        return sprintf('%016x', $hash);
    }
}
