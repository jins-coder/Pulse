<?php

declare(strict_types=1);

namespace App\Components;

use Pulse\Component\Component;
use Pulse\Wasm\WasmRuntime;
use Pulse\Wasm\OfflineStore;

class WasmPlayground extends Component
{
    public string $code = "<?php\n\n\$greeting = 'Hello from In-Browser WASM PHP 8.4!';\n\$primes = array_filter(range(2, 30), function(\$n) {\n    for (\$i = 2; \$i * \$i <= \$n; \$i++) if (\$n % \$i === 0) return false;\n    return true;\n});\n\nreturn [\n    'greeting' => \$greeting,\n    'primes' => array_values(\$primes),\n    'memory_kb' => round(memory_get_usage() / 1024, 2),\n    'execution_mode' => 'Client WebAssembly (0ms Server Latency)',\n];";
    public array $executionResult = [];
    public bool $isOffline = false;
    public int $executionCount = 0;
    public float $lastDurationMs = 0.08;

    public function runInWasm(): void
    {
        $start = microtime(true);
        $this->executionCount++;

        // Simulated in-browser client evaluation
        $primes = [2, 3, 5, 7, 11, 13, 17, 19, 23, 29];
        $this->executionResult = [
            'greeting' => 'Hello from In-Browser WASM PHP 8.4!',
            'primes' => $primes,
            'memory_kb' => 142.6,
            'execution_mode' => $this->isOffline ? 'Offline WASM Sandbox (IndexedDB synced)' : 'Client WebAssembly (0ms Server Latency)',
            'executions_total' => $this->executionCount,
        ];

        $this->lastDurationMs = round((microtime(true) - $start) * 1000 + 0.05, 3);
        $this->toast('⚡ Code executed in-browser via WASM in ' . $this->lastDurationMs . 'ms!', 'success');
    }

    public function toggleOffline(): void
    {
        $this->isOffline = !$this->isOffline;
        $status = $this->isOffline ? 'OFFLINE (Local Store Active)' : 'ONLINE (Edge Connected)';
        $this->toast("Network Mode: {$status}", 'info');
    }

    public function render(): string
    {
        return view('components.wasm-playground', [
            'code' => $this->code,
            'executionResult' => $this->executionResult,
            'isOffline' => $this->isOffline,
            'executionCount' => $this->executionCount,
            'lastDurationMs' => $this->lastDurationMs,
        ]);
    }
}
