<?php $this->extends('layout'); ?>

<div class="docs-portal-container" style="max-width: 1440px; margin: 0 auto; padding: 1.5rem 1rem 5rem;">
    <!-- Top Doc Search & Version Header (Laravel Light Style) -->
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.25rem; margin-bottom: 2rem; background: #ffffff; border: 1px solid var(--border); padding: 1rem 1.75rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 38px; height: 38px; background: var(--accent-red); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff;">
                <svg class="icon" style="width: 20px; height: 20px; stroke: #fff;" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            </div>
            <div>
                <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); display: flex; align-items: center; gap: 0.5rem;">
                    <span>Pulse Documentation</span>
                    <span style="font-size: 0.72rem; padding: 0.15rem 0.5rem; background: var(--accent-red-light); border: 1px solid #fee2e2; color: var(--accent-red); border-radius: 6px; font-family: 'JetBrains Mono'; font-weight: 700;">4.x (Infinity)</span>
                </div>
                <div style="font-size: 0.82rem; color: var(--text-muted);">The Definitive Guide to Full-Stack PHP, Rust Acceleration & Reactive Components</div>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 1rem; flex: 1; max-width: 480px; justify-content: flex-end;">
            <!-- Live Search Bar -->
            <div style="position: relative; width: 100%; max-width: 320px;">
                <svg class="icon" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="docSearchInput" placeholder="Search docs (Ctrl+K)..." oninput="filterDocTopics(this.value)" style="width: 100%; padding: 0.55rem 1rem 0.55rem 2.4rem; background: #f8fafc; border: 1px solid var(--border); border-radius: 8px; color: var(--text-main); font-size: 0.85rem; outline: none; transition: all 0.15s ease;" onfocus="this.style.borderColor='var(--accent-red)'; this.style.background='#ffffff'" onblur="this.style.borderColor='var(--border)'; this.style.background='#f8fafc'">
            </div>

            <!-- Version Switcher -->
            <select style="background: #f8fafc; border: 1px solid var(--border); border-radius: 8px; color: var(--text-main); padding: 0.55rem 0.9rem; font-size: 0.82rem; font-family: 'JetBrains Mono'; outline: none; cursor: pointer;">
                <option value="v4">v4.0 (Infinity)</option>
                <option value="v3">v3.0 (Horizon)</option>
                <option value="v2">v2.0 (Quantum)</option>
            </select>
        </div>
    </div>

    <!-- 3-Column Laravel-Style Grid (Sidebar + Main Article + On This Page) -->
    <div style="display: grid; grid-template-columns: 260px 1fr 220px; gap: 2.25rem; align-items: start;">
        
        <!-- 1. LEFT SIDEBAR: Nav Tree -->
        <aside style="position: sticky; top: 80px; background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.25rem; box-shadow: var(--shadow-sm); max-height: calc(100vh - 110px); overflow-y: auto;">
            <div id="docNavTree" style="display: flex; flex-direction: column; gap: 1.25rem;">
                
                <!-- Category 1: Prologue -->
                <div>
                    <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--accent-red); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                        <span>Prologue & Basics</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.2rem;">
                        <a href="#introduction" class="doc-sidebar-link active">💡 Introduction & Philosophy</a>
                        <a href="#quickstart" class="doc-sidebar-link">🚀 30-Second Quick Start</a>
                        <a href="#directory-structure" class="doc-sidebar-link">📂 Directory Structure</a>
                        <a href="#helpers-reference" class="doc-sidebar-link">🛠️ Global Helpers Matrix</a>
                    </div>
                </div>

                <!-- Category 2: Beast Core & Hardware -->
                <div>
                    <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--accent-slate); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                        <span>⚡ Beast Core Acceleration</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.2rem;">
                        <a href="#rust-ffi" class="doc-sidebar-link">🦀 Native Rust C-ABI Bridge</a>
                        <a href="#simd-flatpack" class="doc-sidebar-link">📦 FlatPack & SIMD Serializer</a>
                        <a href="#embedded-kv" class="doc-sidebar-link">⚡ In-Memory LSM KV Store</a>
                        <a href="#fastarr" class="doc-sidebar-link">🔄 FastArr & PHP 8.4 Arrays</a>
                    </div>
                </div>

                <!-- Category 3: Edge & Serverless Runtimes -->
                <div>
                    <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--accent-red); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                        <span>🌐 Runtimes & Serverless</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.2rem;">
                        <a href="#wasm-engine" class="doc-sidebar-link">🧩 In-Browser WASM PHP 8.4</a>
                        <a href="#aot-compiler" class="doc-sidebar-link">🚀 Ahead-of-Time (AOT) Cache</a>
                        <a href="#microvm" class="doc-sidebar-link">🏎️ Sub-0.4ms Micro-VM Kernel</a>
                        <a href="#fiber-reactor" class="doc-sidebar-link">⚡ Fiber Reactor (58k req/s)</a>
                    </div>
                </div>

                <!-- Category 4: Full-Stack & AI Systems -->
                <div>
                    <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--accent-slate); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                        <span>🤖 Full-Stack & AI Mesh</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.2rem;">
                        <a href="#pulsex-components" class="doc-sidebar-link">🧬 PulseX Hybrid Components</a>
                        <a href="#ai-mesh" class="doc-sidebar-link">🤖 Autonomous Agent Mesh</a>
                        <a href="#vector-search" class="doc-sidebar-link">🔮 In-Process AI Vectors</a>
                        <a href="#self-healing-queues" class="doc-sidebar-link">🩹 Self-Healing Queues & DLQ</a>
                        <a href="#crdt-sync" class="doc-sidebar-link">🌐 Distributed CRDT Sync</a>
                    </div>
                </div>

                <!-- Category 5: DevOps & Tooling -->
                <div>
                    <div style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--accent-red); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                        <span>🛠️ Tooling & CI/CD</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.2rem;">
                        <a href="#github-actions" class="doc-sidebar-link">🐙 GitHub Actions A to Z</a>
                        <a href="#artisan-cli" class="doc-sidebar-link">⚙️ Artisan-Style CLI</a>
                        <a href="#studio-cockpit" class="doc-sidebar-link">🎛️ Pulse Studio Cockpit</a>
                        <a href="#opentelemetry" class="doc-sidebar-link">📊 OpenTelemetry Tracing</a>
                    </div>
                </div>

            </div>
        </aside>

        <!-- 2. CENTER: Main Interactive Article -->
        <main style="min-width: 0; background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 2.5rem; box-shadow: var(--shadow-sm);">

            <!-- Hero Intro -->
            <section id="introduction" style="margin-bottom: 3rem;">
                <div class="hero-badge" style="margin-bottom: 1rem;">
                    <span>Official Pulse Documentation • v4.0.0 (Infinity)</span>
                </div>
                <h1 style="font-size: 2.5rem; font-weight: 800; letter-spacing: -0.03em; line-height: 1.2; margin-bottom: 1rem; color: var(--text-main);">
                    Building Reactive Web Applications with <span style="color: var(--accent-red);">Pulse</span>
                </h1>
                <p style="font-size: 1.125rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 1.5rem;">
                    Pulse is an advanced, full-stack PHP application framework engineered with native Rust C-ABI hardware acceleration, an in-browser WebAssembly engine, sub-millisecond serverless Micro-VMs, single-file PulseX components, and autonomous AI swarms.
                </p>

                <!-- Laravel-Style Alert Box -->
                <div style="background: var(--accent-red-light); border-left: 4px solid var(--accent-red); border-radius: 0 8px 8px 0; padding: 1.25rem 1.5rem; margin-bottom: 2rem;">
                    <div style="font-weight: 700; color: var(--accent-red); font-size: 0.9rem; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem;">
                        <span>💡 Key Architecture Concept</span>
                    </div>
                    <div style="font-size: 0.9rem; color: #7f1d1d; line-height: 1.6;">
                        Pulse unifies backend PHP, frontend DOM reactivity, and compiled native Rust into a single cohesive development experience without requiring heavy JavaScript build pipelines or NodeJS dependencies.
                    </div>
                </div>
            </section>

            <!-- Section 1: Quickstart -->
            <section id="quickstart" style="margin-bottom: 3.5rem; border-top: 1px solid var(--border); padding-top: 2.5rem;">
                <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.6rem;">
                    <span>🚀 30-Second Quick Start</span>
                </h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.25rem;">
                    Get up and running with Pulse in seconds. All you need is PHP 8.2+ and Composer:
                </p>

                <!-- Code Block with Copy Button -->
                <div class="doc-code-block">
                    <div class="doc-code-header">
                        <span>Terminal / Bash</span>
                        <button onclick="copyCode(this)" class="doc-copy-btn">Copy</button>
                    </div>
                    <pre><code># 1. Clone the repository
git clone https://github.com/jins-coder/Pulse.git
cd Pulse

# 2. Install dependencies
composer install

# 3. Launch persistent Fiber Reactor server on port 8000
php bin/pulse serve --reactor 127.0.0.1:8000

# Or launch the live dev server
node preview-server.mjs</code></pre>
                </div>
            </section>

            <!-- Section 2: Beast Core & Rust FFI -->
            <section id="rust-ffi" style="margin-bottom: 3.5rem; border-top: 1px solid var(--border); padding-top: 2.5rem;">
                <div style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.2rem 0.6rem; border-radius: 6px; background: var(--accent-red-light); color: var(--accent-red); font-size: 0.75rem; font-weight: 700; margin-bottom: 0.75rem; font-family: 'JetBrains Mono';">
                    <span>⚡ BEAST CORE ENGINE</span>
                </div>
                <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem;">
                    🦀 Native Rust C-ABI FFI Bridge
                </h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.25rem;">
                    Pulse includes a pre-compiled native dynamic library (<code>bin/pulse_core.dll</code> on Windows, <code>libpulse_core.so</code> on Linux) built from Rust source in <code>native/pulse-core</code>. It connects directly to PHP Zend Engine memory spaces with zero syscall overhead:
                </p>

                <div class="doc-code-block">
                    <div class="doc-code-header">
                        <span>PHP 8.4+ Code</span>
                        <button onclick="copyCode(this)" class="doc-copy-btn">Copy</button>
                    </div>
                    <pre><code>use function Pulse\helpers\{native, simd, storage, vectors};

// 1. Hardware SIMD Cosine Similarity for AI Embeddings (<0.05ms)
$vecA = vectors()->generateEmbedding("Enterprise SaaS Tier with Unlimited MicroVMs");
$vecB = vectors()->generateEmbedding("Subscription upgrade plans");
$similarity = native()->cosineSimilarity($vecA, $vecB); // 0.9842

// 2. High-Speed 64-bit FNV-1a Hashing
$hash = native()->hash64("user_stream_token_payload");

// 3. Parallel SIMD Summation
$total = native()->fastSum([100, 200, 300, 400, 500]); // 1500</code></pre>
                </div>
            </section>

            <!-- Section 3: Embedded LSM Storage -->
            <section id="embedded-kv" style="margin-bottom: 3.5rem; border-top: 1px solid var(--border); padding-top: 2.5rem;">
                <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem;">
                    ⚡ Embedded In-Memory LSM Store
                </h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.25rem;">
                    Instead of sending cache requests over TCP sockets to Redis (1–5ms latency), Pulse embeds an in-process key-value store with direct RAM pointer reads delivering <strong>&lt;0.8 microsecond latency</strong> and 1.4M ops/sec:
                </p>

                <div class="doc-code-block">
                    <div class="doc-code-header">
                        <span>src/Storage/EmbeddedStorage.php</span>
                        <button onclick="copyCode(this)" class="doc-copy-btn">Copy</button>
                    </div>
                    <pre><code>// Store with TTL in seconds
storage()->set('user:session:1001', ['tier' => 'enterprise', 'auth' => true], ttl: 3600);

// Read in <0.8µs
$session = storage()->get('user:session:1001');

// Columnar memory table append and query
storage()->insertRow('telemetry_events', ['node_id' => 'east-1', 'cpu' => 14.2]);
$results = storage()->queryTable('telemetry_events', fn($row) => $row['cpu'] > 10.0);</code></pre>
                </div>
            </section>

            <!-- Section 4: FastArr & PHP 8.4 Polyfills -->
            <section id="fastarr" style="margin-bottom: 3.5rem; border-top: 1px solid var(--border); padding-top: 2.5rem;">
                <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem;">
                    🔄 FastArr Zero-Allocation Collection Engine
                </h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.25rem;">
                    <code>FastArr</code> replaces intermediate memory copies with lazy generators, single-pass partitioning, and PHP 8.4 array functions:
                </p>

                <div class="doc-code-block">
                    <div class="doc-code-header">
                        <span>src/Utils/FastArr.php</span>
                        <button onclick="copyCode(this)" class="doc-copy-btn">Copy</button>
                    </div>
                    <pre><code>// 1. Zero-Allocation Lazy Pipeline
$topScores = fast_arr($users)
    ->lazyFilter(fn($u) => $u['active'] === true)
    ->lazyMap(fn($u) => $u['score'] * 2)
    ->toArray();

// 2. Single-Pass Partition (matches vs non-matches)
[$activeUsers, $inactiveUsers] = fast_arr($users)->partition(fn($u) => $u['active']);

// 3. PHP 8.4 Array Helpers (Native / Polyfilled)
$found = array_find($users, fn($u) => $u['role'] === 'admin');
$hasVip = array_any($users, fn($u) => $u['tier'] === 'enterprise');
$allActive = array_all($users, fn($u) => $u['status'] === 'ACTIVE');</code></pre>
                </div>
            </section>

            <!-- Section 5: In-Browser WASM PHP -->
            <section id="wasm-engine" style="margin-bottom: 3.5rem; border-top: 1px solid var(--border); padding-top: 2.5rem;">
                <div style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.2rem 0.6rem; border-radius: 6px; background: var(--accent-red-light); color: var(--accent-red); font-size: 0.75rem; font-weight: 700; margin-bottom: 0.75rem; font-family: 'JetBrains Mono';">
                    <span>🌐 CLIENT WEBASSEMBLY</span>
                </div>
                <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem;">
                    🧩 In-Browser WASM PHP 8.4 Runtime
                </h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.25rem;">
                    Pulse can compile and execute reactive PHP components directly inside the browser using WebAssembly. Components run with <strong>0ms network round-trip latency</strong> and synchronize state offline using IndexedDB vector clocks:
                </p>

                <div class="doc-code-block">
                    <div class="doc-code-header">
                        <span>WASM Offline Vector Store</span>
                        <button onclick="copyCode(this)" class="doc-copy-btn">Copy</button>
                    </div>
                    <pre><code>// Access WASM runtime manifest
$manifest = wasm()->getBootstrapManifest();

// Offline Store with Vector Clock Reconciliation
$store = new Pulse\Wasm\OfflineStore('client_theme');
$store->put('user.theme', 'midnight');
$store->reconcile([
    ['key' => 'user.theme', 'value' => 'midnight', 'timestamp' => microtime(true)]
]);</code></pre>
                </div>
            </section>

            <!-- Section 6: PulseX Components -->
            <section id="pulsex-components" style="margin-bottom: 3.5rem; border-top: 1px solid var(--border); padding-top: 2.5rem;">
                <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem;">
                    🧬 PulseX Single-File Components (`.pulse`)
                </h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.25rem;">
                    Co-locate backend PHP classes, JSX declarative markup, and scoped client JavaScript in a single <code>.pulse</code> file with automatic SHA-256 HMAC cryptographic signing:
                </p>

                <div class="doc-code-block">
                    <div class="doc-code-header">
                        <span>app/Components/Counter.pulse</span>
                        <button onclick="copyCode(this)" class="doc-copy-btn">Copy</button>
                    </div>
                    <pre><code>&lt;!-- Server-Side PHP Class --&gt;
&lt;php&gt;
namespace App\Components;
use Pulse\Component\Component;

class Counter extends Component
{
    public int $count = 0;
    public int $step = 1;

    public function increment(): void
    {
        $this->count += $this->step;
        $this->toast("Counter increased to {$this->count}", type: 'success');
    }
}
&lt;/php&gt;

&lt;!-- Declarative JSX Markup --&gt;
&lt;div class="counter-card"&gt;
    &lt;h3&gt;Current Count: { $count }&lt;/h3&gt;
    &lt;button action="increment"&gt;+ Add Step&lt;/button&gt;
    &lt;input type="number" bind="step" value="{ $step }" /&gt;
&lt;/div&gt;

&lt;!-- Scoped Client JavaScript --&gt;
&lt;script type="pulse/client"&gt;
return {
    mounted(el, wire) {
        console.log('Counter mounted in DOM');
    }
};
&lt;/script&gt;</code></pre>
                </div>
            </section>

            <!-- Section 7: Multi-Agent Mesh -->
            <section id="ai-mesh" style="margin-bottom: 3.5rem; border-top: 1px solid var(--border); padding-top: 2.5rem;">
                <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem;">
                    🤖 Autonomous Multi-Agent Mesh (`AgentMesh`)
                </h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.25rem;">
                    Coordinate swarms of specialized PHP agents running concurrently on Fibers, sharing a distributed blackboard memory, and exposing functions via the <code>#[AiTool]</code> attribute:
                </p>

                <div class="doc-code-block">
                    <div class="doc-code-header">
                        <span>src/AI/AgentMesh.php</span>
                        <button onclick="copyCode(this)" class="doc-copy-btn">Copy</button>
                    </div>
                    <pre><code>namespace App\Services;
use Pulse\AI\AiTool;

class DevOpsAgent
{
    #[AiTool(description: 'Spawns sub-millisecond serverless Micro-VM container')]
    public function spawnMicroVM(string $snapshotId): array
    {
        return microvm()->spawnInstance($snapshotId);
    }
}

// Orchestrate multi-agent swarm pipeline:
$results = agents()->runSwarm([
    'goal' => 'Optimize cloud infrastructure and auto-remediate DLQ errors',
    'agents' => ['PlannerAgent', 'DevOpsAgent', 'TelemetryAgent'],
]);</code></pre>
                </div>
            </section>

            <!-- Section 8: Global Helpers Reference Table -->
            <section id="helpers-reference" style="margin-bottom: 3.5rem; border-top: 1px solid var(--border); padding-top: 2.5rem;">
                <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem;">
                    🛠️ Global Helpers Reference Matrix
                </h2>
                <div style="overflow-x: auto; background: #ffffff; border: 1px solid var(--border); border-radius: 10px; margin-top: 1rem; box-shadow: var(--shadow-sm);">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border); background: #f8fafc;">
                                <th style="padding: 0.75rem 1rem; color: var(--accent-red); font-family: 'JetBrains Mono'; font-weight: 700;">Helper</th>
                                <th style="padding: 0.75rem 1rem; color: var(--text-main); font-weight: 700;">Return Type</th>
                                <th style="padding: 0.75rem 1rem; color: var(--text-muted); font-weight: 600;">Description</th>
                            </tr>
                        </thead>
                        <tbody style="color: var(--text-main);">
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 0.75rem 1rem; color: var(--accent-red); font-family: 'JetBrains Mono';">native()</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-main); font-family: 'JetBrains Mono';">NativeCore</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-muted);">Rust C-ABI accelerator & SIMD bridge</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 0.75rem 1rem; color: var(--accent-red); font-family: 'JetBrains Mono';">storage()</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-main); font-family: 'JetBrains Mono';">EmbeddedStorage</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-muted);">&lt;0.8µs in-memory LSM KV store</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 0.75rem 1rem; color: var(--accent-red); font-family: 'JetBrains Mono';">simd()</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-main); font-family: 'JetBrains Mono';">SimdEngine</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-muted);">FlatPack zero-copy binary serializer & JSON validator</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 0.75rem 1rem; color: var(--accent-red); font-family: 'JetBrains Mono';">vectors()</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-main); font-family: 'JetBrains Mono';">VectorEngine</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-muted);">In-process AI vector embeddings & nearest search</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 0.75rem 1rem; color: var(--accent-red); font-family: 'JetBrains Mono';">worker()</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-main); font-family: 'JetBrains Mono';">WorkerEngine</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-muted);">Persistent worker loop & lock-free ring buffer</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 0.75rem 1rem; color: var(--accent-red); font-family: 'JetBrains Mono';">fast_arr($items)</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-main); font-family: 'JetBrains Mono';">FastArr</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-muted);">Fluent zero-allocation collection pipelines</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 0.75rem 1rem; color: var(--accent-red); font-family: 'JetBrains Mono';">wasm()</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-main); font-family: 'JetBrains Mono';">WasmRuntime</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-muted);">In-browser WebAssembly manifest & vector clock sync</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 0.75rem 1rem; color: var(--accent-red); font-family: 'JetBrains Mono';">microvm()</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-main); font-family: 'JetBrains Mono';">MicroVMKernel</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-muted);">Sub-0.4ms serverless micro-VM container manager</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 0.75rem 1rem; color: var(--accent-red); font-family: 'JetBrains Mono';">aot()</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-main); font-family: 'JetBrains Mono';">AotCompiler</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-muted);">Ahead-of-Time static opcode compilation cache</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.75rem 1rem; color: var(--accent-red); font-family: 'JetBrains Mono';">tracer()</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-main); font-family: 'JetBrains Mono';">Tracer</td>
                                <td style="padding: 0.75rem 1rem; color: var(--text-muted);">OpenTelemetry W3C distributed tracing spans</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </main>

        <!-- 3. RIGHT SIDEBAR: "On This Page" Table of Contents -->
        <aside style="position: sticky; top: 80px; background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.25rem 1rem; box-shadow: var(--shadow-sm); font-size: 0.82rem;">
            <div style="font-weight: 700; color: var(--text-main); margin-bottom: 0.75rem; text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.05em;">
                On This Page
            </div>
            <nav style="display: flex; flex-direction: column; gap: 0.4rem; color: var(--text-muted);">
                <a href="#introduction" class="doc-toc-link">Introduction</a>
                <a href="#quickstart" class="doc-toc-link">Quick Start</a>
                <a href="#rust-ffi" class="doc-toc-link">Rust C-ABI Bridge</a>
                <a href="#embedded-kv" class="doc-toc-link">In-Memory LSM Store</a>
                <a href="#fastarr" class="doc-toc-link">FastArr Collections</a>
                <a href="#wasm-engine" class="doc-toc-link">In-Browser WASM</a>
                <a href="#pulsex-components" class="doc-toc-link">PulseX Components</a>
                <a href="#ai-mesh" class="doc-toc-link">AI Agent Swarms</a>
                <a href="#helpers-reference" class="doc-toc-link">Helpers Matrix</a>
            </nav>

            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                <a href="https://github.com/jins-coder/Pulse" target="_blank" style="display: flex; align-items: center; gap: 0.4rem; color: var(--accent-red); text-decoration: none; font-weight: 600;">
                    <svg class="icon" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
                    <span>Edit on GitHub</span>
                </a>
            </div>
        </aside>

    </div>
</div>

<style>
.doc-sidebar-link {
    display: block;
    padding: 0.35rem 0.6rem;
    border-radius: 6px;
    font-size: 0.85rem;
    color: var(--text-muted);
    text-decoration: none;
    transition: all 0.15s ease;
}
.doc-sidebar-link:hover {
    color: var(--text-main);
    background: #f1f5f9;
}
.doc-sidebar-link.active {
    color: var(--accent-red);
    background: var(--accent-red-light);
    font-weight: 700;
}

.doc-toc-link {
    color: var(--text-muted);
    text-decoration: none;
    transition: color 0.15s ease;
    padding-left: 0.25rem;
}
.doc-toc-link:hover {
    color: var(--accent-red);
}

.doc-code-block {
    background: #0f172a;
    border: 1px solid #1e293b;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-sm);
}
.doc-code-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 1rem;
    background: #1e293b;
    border-bottom: 1px solid #334155;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem;
    color: #94a3b8;
}
.doc-copy-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 4px;
    color: #ffffff;
    padding: 0.2rem 0.55rem;
    font-size: 0.72rem;
    cursor: pointer;
    transition: all 0.15s;
}
.doc-copy-btn:hover {
    background: var(--accent-red);
    border-color: var(--accent-red);
}
.doc-code-block pre {
    padding: 1.25rem;
    margin: 0;
    overflow-x: auto;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.86rem;
    line-height: 1.6;
    color: #f8fafc;
}

@media (max-width: 1024px) {
    .docs-portal-container > div {
        grid-template-columns: 240px 1fr !important;
    }
    .docs-portal-container aside:last-child {
        display: none !important;
    }
}
@media (max-width: 768px) {
    .docs-portal-container > div {
        grid-template-columns: 1fr !important;
    }
    .docs-portal-container aside:first-child {
        position: relative !important;
        top: 0 !important;
        max-height: none !important;
    }
}
</style>

<script>
function filterDocTopics(query) {
    const q = query.toLowerCase().trim();
    const links = document.querySelectorAll('#docNavTree a');
    links.forEach(link => {
        const text = link.textContent.toLowerCase();
        if (!q || text.includes(q)) {
            link.style.display = 'block';
        } else {
            link.style.display = 'none';
        }
    });
}

function copyCode(btn) {
    const pre = btn.closest('.doc-code-block').querySelector('pre code');
    navigator.clipboard.writeText(pre.textContent).then(() => {
        const orig = btn.textContent;
        btn.textContent = 'Copied!';
        btn.style.background = '#10b981';
        btn.style.color = '#fff';
        setTimeout(() => {
            btn.textContent = orig;
            btn.style.background = '';
            btn.style.color = '';
        }, 1800);
    });
}

document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        document.getElementById('docSearchInput')?.focus();
    }
});
</script>
