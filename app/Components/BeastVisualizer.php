<?php

declare(strict_types=1);

namespace App\Components;

use Pulse\Component\Component;
use Pulse\Core\NativeCore;
use Pulse\Storage\EmbeddedStorage;
use Pulse\Utils\SimdEngine;
use Pulse\AI\VectorEngine;
use Pulse\Runtime\WorkerEngine;

class BeastVisualizer extends Component
{
    public string $engineMode = 'Detecting...';
    public bool $isNative = false;
    public float $vectorSearchTimeMs = 0.0;
    public array $vectorResults = [];
    public array $storageStats = [];
    public array $workerMetrics = [];
    public string $jsonBenchmark = '';
    public int $simdOperationsCount = 0;

    public function mount(): void
    {
        $core = NativeCore::getInstance();
        $this->engineMode = $core->getEngineDescription();
        $this->isNative = $core->isNative();

        // Populate mock vector dataset for instant AI semantic search testing
        $vectors = VectorEngine::getInstance();
        if ($vectors->count() === 0) {
            $vectors->insert('doc_1', $vectors->generateEmbedding('Upgrade to Enterprise Subscription Tier with Unlimited MicroVMs'), [
                'title' => 'Enterprise Plan Documentation',
                'category' => 'Billing',
            ]);
            $vectors->insert('doc_2', $vectors->generateEmbedding('High speed SIMD JSON parser and flatpack serializer in Rust'), [
                'title' => 'SIMD Architecture Guide',
                'category' => 'Core Engine',
            ]);
            $vectors->insert('doc_3', $vectors->generateEmbedding('CRDT vector clock conflict resolution for offline realtime sync'), [
                'title' => 'Realtime Sync Specification',
                'category' => 'Realtime',
            ]);
            $vectors->insert('doc_4', $vectors->generateEmbedding('AI Multi-Agent Swarm with autonomous consensus and task routing'), [
                'title' => 'Agent Swarm Protocol',
                'category' => 'AI Mesh',
            ]);
        }

        // Initialize embedded storage stats
        $storage = EmbeddedStorage::getInstance();
        $storage->set('sys.uptime', time());
        $storage->set('sys.cluster', 'primary-edge-01');
        $this->storageStats = $storage->stats();

        // Worker engine status
        $this->workerMetrics = WorkerEngine::getInstance()->getStatus();
    }

    public function runVectorSearch(string $query = 'Enterprise billing and MicroVM plans'): void
    {
        $vectors = VectorEngine::getInstance();
        $start = microtime(true);
        $queryVec = $vectors->generateEmbedding($query);
        $this->vectorResults = $vectors->search($queryVec, 3);
        $this->vectorSearchTimeMs = round((microtime(true) - $start) * 1000, 3);
    }

    public function runSimdPackBenchmark(): void
    {
        $start = microtime(true);
        $samplePayload = [
            'cluster_id' => 'beast_01',
            'nodes' => array_map(fn($i) => ['id' => $i, 'load' => rand(10, 99), 'status' => 'OK'], range(1, 200)),
            'timestamp' => microtime(true),
        ];

        $packed = SimdEngine::pack($samplePayload);
        $unpacked = SimdEngine::unpack($packed);
        $this->simdOperationsCount += count($samplePayload['nodes']);
        $duration = round((microtime(true) - $start) * 1000, 3);
        $this->jsonBenchmark = "Packed & Unpacked 200 nodes in {$duration}ms (FlatPack: " . strlen($packed) . " bytes)";
    }

    public function render(): string
    {
        return view('components/beast-visualizer', [
            'engineMode' => $this->engineMode,
            'isNative' => $this->isNative,
            'vectorSearchTimeMs' => $this->vectorSearchTimeMs,
            'vectorResults' => $this->vectorResults,
            'storageStats' => $this->storageStats,
            'workerMetrics' => $this->workerMetrics,
            'jsonBenchmark' => $this->jsonBenchmark,
            'simdOperationsCount' => $this->simdOperationsCount,
        ]);
    }
}
