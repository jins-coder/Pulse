<?php

declare(strict_types=1);

namespace Pulse\Wasm;

use Pulse\Component\Component;

/**
 * In-Browser WebAssembly (WASM) PHP Bridge for Pulse Framework v4.0 (Infinity).
 * Coordinates client-side execution, offline state mutation, and DOM morph diff generation.
 */
class WasmRuntime
{
    private static ?WasmRuntime $instance = null;
    protected array $registeredComponents = [];
    protected array $wasmModuleConfig = [];
    protected bool $offlineModeEnabled = true;

    public function __construct(array $config = [])
    {
        $this->wasmModuleConfig = array_merge([
            'wasm_binary_url' => '/pulse-engine.wasm',
            'memory_initial_pages' => 256, // 16MB initial
            'memory_max_pages' => 2048,    // 128MB max
            'enable_opcache' => true,
            'enable_jit' => true,
        ], $config);

        self::$instance = $this;
    }

    public static function getInstance(): WasmRuntime
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function registerClientComponent(string $componentClass): self
    {
        $this->registeredComponents[$componentClass] = [
            'class' => $componentClass,
            'registered_at' => microtime(true),
            'wasm_compatible' => true,
        ];
        return $this;
    }

    public function getRegisteredComponents(): array
    {
        return $this->registeredComponents;
    }

    public function getBootstrapManifest(): array
    {
        return [
            'engine' => 'Pulse-WASM-v4.0',
            'version' => '4.0.0',
            'config' => $this->wasmModuleConfig,
            'offline_capable' => $this->offlineModeEnabled,
            'components' => array_keys($this->registeredComponents),
            'timestamp' => microtime(true),
        ];
    }

    public function compileForWasm(Component $component): array
    {
        return [
            'id' => $component->id,
            'class' => get_class($component),
            'state' => $component->getPublicState(),
            'html' => $component->toHtml(),
            'wasm_byte_size' => strlen(serialize($component)),
            'compiled_at' => microtime(true),
        ];
    }
}
