<?php $this->extends('layout'); ?>

<div class="container">
    <div class="hero">
        <div class="hero-badge" style="background: rgba(244, 63, 94, 0.12); border-color: rgba(244, 63, 94, 0.35); color: #fb7185;">
            <svg class="icon" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            <span>v4.0.0 Infinity • In-Browser WASM PHP • AOT Opcode Compilation • Sub-1ms Micro-VMs</span>
        </div>
        <h1>PHP runs anywhere.<br><span>From Cloud Fibers to Browser WASM.</span></h1>
        <p>A breakthrough full-stack runtime running the exact same PHP components on in-memory server Fiber reactors, client-side browser WebAssembly engines, and instant serverless Micro-VM containers.</p>
        
        <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 1.75rem; flex-wrap: wrap;">
            <a href="/wasm" class="btn btn-primary" style="background: linear-gradient(135deg, #38bdf8, #818cf8); box-shadow: 0 4px 18px rgba(56, 189, 248, 0.5); font-size: 1rem; padding: 0.75rem 1.75rem;">
                ⚡ Try In-Browser WASM PHP
            </a>
            <a href="/aot" class="btn btn-secondary" style="font-size: 1rem; padding: 0.75rem 1.75rem; border-color: rgba(168, 85, 247, 0.4); color: #c084fc;">
                🚀 AOT & Micro-VM Benchmarks
            </a>
            <a href="/agents" class="btn btn-secondary" style="font-size: 1rem; padding: 0.75rem 1.75rem;">
                🤖 AI Multi-Agent Swarm
            </a>
            <a href="/_pulse/studio" class="btn btn-secondary" style="font-size: 1rem; padding: 0.75rem 1.75rem;">
                🎛️ Studio Cockpit
            </a>
        </div>
    </div>

    <!-- Feature Showcase Grid -->
    <div class="grid-2">
        <!-- Component 1: PulseX Hybrid Component (Canvas Chart + Server State) -->
        <div class="card" style="grid-column: 1 / -1;">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M3 3v18h18"></path><path d="M18 17V9"></path><path d="M13 17V5"></path><path d="M8 17v-3"></path></svg>
                    </div>
                    <span>PulseX Hybrid Component: Live Chart (PHP + Client Canvas JS)</span>
                </div>
                <span class="badge">
                    <svg class="icon" style="width: 12px; height: 12px;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    <span>Co-Located JS & PHP</span>
                </span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">
                This component authors PHP server business logic and client-side HTML5 canvas graphics in the <strong>exact same component</strong> with zero build pipeline.
            </p>

            <?= component(\App\Components\AnalyticsWidget::class) ?>
        </div>

        <!-- Component 2: Counter -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon">
                        <svg class="icon" viewBox="0 0 24 24"><rect x="4" y="2" width="16" height="20" rx="2"></rect><line x1="8" y1="6" x2="16" y2="6"></line><line x1="16" y1="14" x2="16" y2="14.01"></line><line x1="12" y1="14" x2="12" y2="14.01"></line><line x1="8" y1="14" x2="8" y2="14.01"></line><line x1="16" y1="18" x2="16" y2="18.01"></line><line x1="12" y1="18" x2="12" y2="18.01"></line><line x1="8" y1="18" x2="8" y2="18.01"></line></svg>
                    </div>
                    <span>Reactive State Counter</span>
                </div>
                <span class="badge">
                    <svg class="icon" style="width: 12px; height: 12px;" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    <span>HMAC Signed</span>
                </span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">
                Clicking buttons dispatches state mutations directly to the PHP class, seamlessly morphing the DOM with cryptographic state verification.
            </p>

            <?= component(\App\Components\Counter::class, ['initial' => 5]) ?>
        </div>

        <!-- Component 3: Live Search -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon">
                        <svg class="icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </div>
                    <span>Instant Live Search</span>
                </div>
                <span class="badge">
                    <svg class="icon" style="width: 12px; height: 12px;" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                    <span>Debounced Sync</span>
                </span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">
                Typing syncs server state with debouncing (150ms) and executes PHP filtering with zero manual API endpoints.
            </p>

            <?= component(\App\Components\UserSearch::class) ?>
        </div>
    </div>
</div>
