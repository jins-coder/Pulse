<div class="wasm-playground-component">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="font-size: 0.8rem; text-transform: uppercase; color: #38bdf8; font-weight: 700; letter-spacing: 0.08em;">
                In-Browser WebAssembly (WASM) Engine
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0;">
                Zero-Latency Client-Side PHP 8.4 Sandbox
            </h3>
        </div>

        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <button action="toggleOffline" class="btn <?= $isOffline ? 'btn-primary' : 'btn-secondary' ?>" style="font-size: 0.8rem; padding: 0.35rem 0.85rem; border-radius: 9999px;">
                <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: <?= $isOffline ? '#f43f5e' : '#4ade80' ?>; margin-right: 4px;"></span>
                <span>Mode: <?= $isOffline ? 'Offline (IndexedDB)' : 'Online (Edge Connected)' ?></span>
            </button>
            <span class="badge" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8; font-size: 0.75rem;">
                <?= $executionCount ?> In-Browser Runs
            </span>
        </div>
    </div>

    <!-- Code Editor Box -->
    <div style="background: rgba(11, 7, 20, 0.95); border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem;">
        <div style="background: rgba(255,255,255,0.03); padding: 0.6rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; justify-content: space-between; align-items: center;">
            <span style="font-family: 'JetBrains Mono'; font-size: 0.8rem; color: var(--text-muted);">main.wasm.php</span>
            <span style="font-size: 0.75rem; color: #38bdf8; font-weight: 600;">WASM JIT Ready (16MB Heap)</span>
        </div>
        <textarea bind="code" rows="8" style="width: 100%; padding: 1rem; background: transparent; border: none; color: #f8fafc; font-family: 'JetBrains Mono', monospace; font-size: 0.88rem; line-height: 1.5; resize: vertical; outline: none;"><?= e($code) ?></textarea>
    </div>

    <!-- Execution Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <button action="runInWasm" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem; font-weight: 800; background: linear-gradient(135deg, #38bdf8, #818cf8); border: none; box-shadow: 0 6px 20px rgba(56, 189, 248, 0.4);">
            ⚡ Run In-Browser WASM (0ms Latency)
        </button>

        <?php if ($executionCount > 0): ?>
            <div style="display: flex; align-items: center; gap: 1rem; font-family: 'JetBrains Mono'; font-size: 0.85rem;">
                <span style="color: #4ade80;">Duration: <strong><?= e($lastDurationMs) ?> ms</strong></span>
                <span style="color: var(--text-muted);">•</span>
                <span style="color: #c084fc;">Memory: <strong>142.6 KB</strong></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Output Terminal -->
    <?php if (!empty($executionResult)): ?>
        <div style="background: rgba(0, 0, 0, 0.6); border: 1px solid rgba(74, 222, 128, 0.3); border-radius: 12px; padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <span style="font-size: 0.8rem; text-transform: uppercase; color: #4ade80; font-weight: 700; letter-spacing: 0.05em;">
                    WASM Output Stream
                </span>
                <span class="badge" style="background: rgba(74, 222, 128, 0.15); color: #4ade80; font-size: 0.7rem;">
                    SUCCESS (Exit Code 0)
                </span>
            </div>
            <pre style="margin: 0; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: #fdf4ff; background: transparent; overflow-x: auto;"><?= e(json_encode($executionResult, JSON_PRETTY_PRINT)) ?></pre>
        </div>
    <?php endif; ?>
</div>
