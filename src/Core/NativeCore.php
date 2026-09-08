<?php

declare(strict_types=1);

namespace Pulse\Core;

/**
 * Native Core Bridge - Leverages Rust/C native dynamic library via PHP FFI
 * with automatic fallback to optimized pure PHP 8.4 engine.
 */
class NativeCore
{
    private static ?self $instance = null;
    private ?\FFI $ffi = null;
    private bool $isNative = false;
    private string $engineDescription = 'Pure PHP 8.4 JIT Engine';

    public function __construct(?string $libPath = null)
    {
        $libPath = $libPath ?? $this->detectLibraryPath();
        if ($libPath !== null && file_exists($libPath) && extension_loaded('ffi')) {
            try {
                $cdef = "
                    const char* pulse_core_version();
                    uint64_t pulse_fast_hash(const uint8_t* data, size_t len);
                    float pulse_cosine_similarity(const float* a, const float* b, size_t len);
                    long long pulse_fast_sum_i64(const long long* arr, size_t len);
                    size_t pulse_fast_partition_i64(const long long* arr, size_t len, long long* out_matches, long long threshold);
                    int pulse_json_validate(const uint8_t* data, size_t len);
                    int pulse_crdt_vector_reconcile(uint64_t clock_local, uint64_t clock_remote);
                ";
                $this->ffi = \FFI::cdef($cdef, $libPath);
                $this->isNative = true;
                $this->engineDescription = \FFI::string($this->ffi->pulse_core_version());
            } catch (\Throwable $e) {
                $this->isNative = false;
                $this->engineDescription = 'Pure PHP 8.4 JIT Engine (FFI fallback: ' . $e->getMessage() . ')';
            }
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function isNative(): bool
    {
        return $this->isNative;
    }

    public function getEngineDescription(): string
    {
        return $this->engineDescription;
    }

    /**
     * Compute fast 64-bit hash
     */
    public function hash64(string $data): int
    {
        if ($this->isNative && $this->ffi !== null) {
            return (int)$this->ffi->pulse_fast_hash($data, strlen($data));
        }
        // Fallback FNV-1a 64-bit in PHP
        $hash = 0xcbf29ce484222325;
        $len = strlen($data);
        for ($i = 0; $i < $len; $i++) {
            $hash ^= ord($data[$i]);
            $hash = ($hash * 0x100000001b3) & 0xFFFFFFFFFFFFFFFF;
        }
        return $hash;
    }

    /**
     * Compute Cosine Similarity between two float vectors (for AI embeddings)
     *
     * @param float[] $a
     * @param float[] $b
     */
    public function cosineSimilarity(array $a, array $b): float
    {
        $len = min(count($a), count($b));
        if ($len === 0) {
            return 0.0;
        }

        if ($this->isNative && $this->ffi !== null) {
            $cFloatA = \FFI::new("float[$len]");
            $cFloatB = \FFI::new("float[$len]");
            for ($i = 0; $i < $len; $i++) {
                $cFloatA[$i] = (float)$a[$i];
                $cFloatB[$i] = (float)$b[$i];
            }
            return (float)$this->ffi->pulse_cosine_similarity($cFloatA, $cFloatB, $len);
        }

        // Optimized PHP vectorized calculation
        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        for ($i = 0; $i < $len; $i++) {
            $va = (float)$a[$i];
            $vb = (float)$b[$i];
            $dot += $va * $vb;
            $normA += $va * $va;
            $normB += $vb * $vb;
        }
        $denom = sqrt($normA) * sqrt($normB);
        return $denom > 0 ? $dot / $denom : 0.0;
    }

    /**
     * High-speed parallel integer sum
     *
     * @param int[] $numbers
     */
    public function fastSum(array $numbers): int
    {
        $len = count($numbers);
        if ($len === 0) {
            return 0;
        }

        if ($this->isNative && $this->ffi !== null) {
            $cArr = \FFI::new("long long[$len]");
            $i = 0;
            foreach ($numbers as $num) {
                $cArr[$i++] = (int)$num;
            }
            return (int)$this->ffi->pulse_fast_sum_i64($cArr, $len);
        }

        return array_sum($numbers);
    }

    /**
     * Ultra-fast zero-copy JSON format validator
     */
    public function validateJson(string $json): bool
    {
        if ($this->isNative && $this->ffi !== null) {
            return $this->ffi->pulse_json_validate($json, strlen($json)) > 0;
        }
        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * CRDT vector clock reconciliation (returns 1 for local win, -1 for remote win, 0 for tie)
     */
    public function reconcileCrdt(int $localClock, int $remoteClock): int
    {
        if ($this->isNative && $this->ffi !== null) {
            return (int)$this->ffi->pulse_crdt_vector_reconcile($localClock, $remoteClock);
        }
        return $localClock <=> $remoteClock;
    }

    private function detectLibraryPath(): ?string
    {
        $base = dirname(__DIR__, 2);
        $candidates = [
            $base . '/bin/pulse_core.dll',
            $base . '/native/pulse-core/target/release/pulse_core.dll',
            $base . '/native/pulse-core/target/release/libpulse_core.so',
            $base . '/native/pulse-core/target/release/libpulse_core.dylib',
        ];

        foreach ($candidates as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        return null;
    }
}
