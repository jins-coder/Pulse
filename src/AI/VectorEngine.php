<?php

declare(strict_types=1);

namespace Pulse\AI;

use Pulse\Core\NativeCore;

/**
 * VectorEngine - In-Process High-Speed Vector Embeddings & Nearest Neighbor Semantic Index
 * Powered by SIMD Cosine Similarity calculations (Rust Native / PHP Fallback).
 */
class VectorEngine
{
    private static ?self $instance = null;
    private NativeCore $core;

    /** @var array<string, array{vector: float[], metadata: array<string, mixed>}> */
    private array $index = [];

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
     * Generate deterministic mock embedding vector (e.g. 128 dimensions) from text for instant local testing
     *
     * @return float[]
     */
    public function generateEmbedding(string $text, int $dims = 64): array
    {
        $vector = [];
        $hash = md5($text);
        for ($i = 0; $i < $dims; $i++) {
            $sub = substr($hash, ($i % 30), 2);
            $val = (hexdec($sub) / 255.0) * 2.0 - 1.0;
            $vector[] = (float)$val;
        }
        return $vector;
    }

    /**
     * Index an item with its vector and metadata
     *
     * @param float[] $vector
     * @param array<string, mixed> $metadata
     */
    public function insert(string $id, array $vector, array $metadata = []): void
    {
        $this->index[$id] = [
            'vector' => $vector,
            'metadata' => $metadata,
        ];
    }

    /**
     * Search nearest neighbors by Cosine Similarity
     *
     * @param float[] $queryVector
     * @return array<int, array{id: string, score: float, metadata: array<string, mixed>}>
     */
    public function search(array $queryVector, int $topK = 5): array
    {
        $results = [];
        foreach ($this->index as $id => $entry) {
            $score = $this->core->cosineSimilarity($queryVector, $entry['vector']);
            $results[] = [
                'id' => $id,
                'score' => round($score, 4),
                'metadata' => $entry['metadata'],
            ];
        }

        // Sort descending by similarity score
        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($results, 0, $topK);
    }

    /**
     * Clear index
     */
    public function clear(): void
    {
        $this->index = [];
    }

    /**
     * Get vector index count
     */
    public function count(): int
    {
        return count($this->index);
    }
}
