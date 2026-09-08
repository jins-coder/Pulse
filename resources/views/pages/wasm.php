<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <!-- Hero Header -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <span class="badge" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.4); margin-bottom: 1rem;">
            ⚡ Pulse v4.0 (Infinity) In-Browser Runtime
        </span>
        <h1 style="font-size: 2.5rem; font-weight: 800; color: #fff; margin-bottom: 0.75rem;">
            In-Browser WebAssembly (WASM) PHP
        </h1>
        <p style="font-size: 1.1rem; color: var(--text-muted); max-width: 750px; margin: 0 auto;">
            Run standard PHP 8.4 code and reactive Pulse components directly inside browser WebAssembly with 0ms server round-trip latency and offline IndexedDB state persistence.
        </p>
    </div>

    <!-- Live WASM Sandbox Component -->
    <div class="glass-card" style="padding: 2rem; margin-bottom: 3rem; border: 1px solid rgba(56, 189, 248, 0.3);">
        <?= component(\App\Components\WasmPlayground::class) ?>
    </div>

    <!-- Architectural Pillars -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        <div class="glass-card" style="padding: 1.5rem;">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🚀</div>
            <h4 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0 0 0.5rem 0;">0ms Client Execution</h4>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.6;">
                Client-side WASM JIT executes component actions instantly without making network HTTP round trips to the server.
            </p>
        </div>

        <div class="glass-card" style="padding: 1.5rem;">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">📴</div>
            <h4 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0 0 0.5rem 0;">Offline-First Local Store</h4>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.6;">
                Mutations are saved to local IndexedDB storage with vector clocks and automatically reconciled with edge nodes when reconnecting.
            </p>
        </div>

        <div class="glass-card" style="padding: 1.5rem;">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🧩</div>
            <h4 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0 0 0.5rem 0;">Universal Component Code</h4>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.6;">
                The exact same <code>.pulse</code> component file runs seamlessly on the server Fiber Reactor or client-side WebAssembly engine.
            </p>
        </div>
    </div>
</div>
