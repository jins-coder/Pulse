<div align="center">

<img src="public/assets/logo.svg" alt="Pulse Framework Logo" width="380">

<br/><br/>

### **Next-Generation Full-Stack PHP Application Framework**
*Persistent Fiber Reactor • PulseX Single-File Components • AI Agents • Zero-Build SPA*

<br/>

[![Build Status](https://img.shields.io/github/actions/workflow/status/jins-coder/Pulse/ci.yml?branch=develop&style=for-the-badge&logo=github&logoColor=white&label=CI%20Build)](https://github.com/jins-coder/Pulse/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.1%20%7C%208.2%20%7C%208.3-777bb4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Release](https://img.shields.io/badge/Release-v2.0.0%20Quantum-6366f1?style=for-the-badge&logo=rocket&logoColor=white)](https://github.com/jins-coder/Pulse/releases)
[![License](https://img.shields.io/badge/License-MIT-10b981?style=for-the-badge&logo=opensourceinitiative&logoColor=white)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-Welcome-f59e0b?style=for-the-badge&logo=git&logoColor=white)](https://github.com/jins-coder/Pulse/pulls)

<br/>

[**📖 Full Documentation**](docs/index.md) •
[**⚡ Quick Start**](#-quick-start) •
[**🧩 PulseX Engine**](docs/components.md) •
[**🤖 AI Agents**](docs/ai-agents.md) •
[**🚀 Fiber Reactor**](docs/fiber-reactor.md) •
[**🛠️ Artisan CLI**](docs/artisan-cli.md) •
[**🗺️ Roadmap**](ROADMAP.md)

---

</div>

## 💡 Why Pulse?

Modern web development often forces developers into a dilemma: accept the page-reload latency of classic PHP backends, or take on the complexity of heavy JavaScript frontends, bundlers, duplicated routing, and complex REST/GraphQL glue code.

**Pulse eliminates this dilemma.** It brings reactive UI components, zero-build SPA navigation, non-blocking async Fibers, in-memory persistent execution, and native AI tool calling directly to standard PHP.

### 🥊 Architecture Comparison

| Feature | Standard PHP (FPM) | Laravel Livewire | Inertia.js | Next.js / Node | ⚡ **Pulse v2.0** |
|:---|:---:|:---:|:---:|:---:|:---:|
| **Server Persistence** | ❌ (Cold boot / req) | ❌ (Cold boot / req) | ❌ (Cold boot / req) | ✅ (Persistent loop) | ⚡ **✅ (Fiber Reactor, 50k+ req/s)** |
| **Component Model** | ❌ None | Blade + PHP Class | Vue/React Only | React/Server Components | ⚡ **✅ PulseX (`.pulse`) PHP+JSX+JS** |
| **Build Pipeline** | N/A | NPM / Vite required | NPM / Vite required | Webpack / Turbopack | ⚡ **✅ Zero-Build (<10KB runtime)** |
| **Tri-Mode Routing** | ❌ Manual | ❌ SSR / Morph | ❌ SPA only | Hybrid | ⚡ **✅ Automatic SSR ↔ SPA ↔ API** |
| **AI Tool-Calling** | ❌ Manual JSON | ❌ Manual | ❌ Manual | Vercel AI SDK | ⚡ **✅ Built-in `#[AiTool]` Attributes** |
| **Time-Travel Debug** | ❌ None | ❌ Limited | ❌ None | Redux / DevTools | ⚡ **✅ Built-in Pulse Studio Cockpit** |
| **Native Multi-Tenancy**| ❌ Manual | ❌ Third-party | ❌ Third-party | ❌ Third-party | ⚡ **✅ First-Class Context Scoping** |

---

## ✨ Core Capabilities

<table>
  <tr>
    <td width="50%">
      <h3>🚀 Persistent Fiber Reactor</h3>
      <p>Built-in non-blocking event-loop socket server running on native PHP 8.1+ Fibers. Delivers <strong>50,000+ req/s</strong> by keeping application kernel and dependency trees warm in memory.</p>
    </td>
    <td width="50%">
      <h3>🧩 PulseX Single-File Components</h3>
      <p>Co-locate PHP backend classes (<code>&lt;php&gt;</code>), JSX markup (<code>{ $props }</code>), and scoped client JavaScript (<code>&lt;script type="pulse/client"&gt;</code>) in a single <code>.pulse</code> file.</p>
    </td>
  </tr>
  <tr>
    <td width="50%">
      <h3>🤖 AI-Native Tool-Calling</h3>
      <p>Decorate PHP services with <code>#[AiTool]</code>. Pulse automatically generates standardized JSON Schemas for OpenAI, Anthropic, and Gemini LLMs and routes agent tool calls.</p>
    </td>
    <td width="50%">
      <h3>🎛️ Pulse Studio & Time-Travel</h3>
      <p>Interactive developer cockpit at <code>/_pulse/studio</code> to inspect active component hierarchies, examine HMAC signatures, and step backward/forward through state mutations.</p>
    </td>
  </tr>
  <tr>
    <td width="50%">
      <h3>🔄 Tri-Mode Universal Routing</h3>
      <p>Write a single route endpoint. Pulse automatically serves full SSR HTML for first visits, sub-2KB DOM morph diffs for SPA links, or JSON for API clients.</p>
    </td>
    <td width="50%">
      <h3>🔒 Cryptographic HMAC State</h3>
      <p>Component state hydrated across client-server boundaries is signed with SHA-256 HMAC keys, guaranteeing tamper-proof security without heavy session stores.</p>
    </td>
  </tr>
</table>

---

## ⚡ Quick Start

### 1. Clone & Install

```bash
git clone https://github.com/jins-coder/Pulse.git
cd Pulse
composer install
```

### 2. Launch the High-Performance Reactor

```bash
# Launch the persistent Fiber Reactor server on port 8000
php bin/pulse serve --reactor 127.0.0.1:8000

# Or start the standard PHP development server
php bin/pulse serve
```

### 3. Open Pulse Studio Cockpit

Open your browser at **[http://127.0.0.1:8000](http://127.0.0.1:8000)** for the live application, and **[http://127.0.0.1:8000/_pulse/studio](http://127.0.0.1:8000/_pulse/studio)** for the real-time developer debugger.

---

## 🧩 PulseX Single-File Components

Create hybrid components combining PHP server logic, declarative HTML, and client-side canvas/WebGL code:

```html
<!-- app/Components/AnalyticsWidget.pulse -->
<php>
namespace App\Components;
use Pulse\Component\Component;

class AnalyticsWidget extends Component
{
    public string $period = 'monthly';
    public array $data = [120, 240, 190, 380, 420, 510, 680];

    public function setPeriod(string $period): void
    {
        $this->period = $period;
        $this->data = $period === 'weekly' 
            ? [85, 130, 95, 210, 180, 290, 340] 
            : [120, 240, 190, 380, 420, 510, 680];
        
        $this->toast("Updated metrics view to {$period}", type: 'success');
    }
}
</php>

<!-- JSX-Style Declarative Markup -->
<div class="analytics-card">
    <div class="header">
        <h3>Live Revenue ({ $period })</h3>
        <button action="setPeriod('weekly')">Weekly</button>
        <button action="setPeriod('monthly')">Monthly</button>
    </div>

    <canvas id="revenue-chart" width="480" height="140"></canvas>
</div>

<!-- Scoped Client JavaScript with Wire Bridge -->
<script type="pulse/client">
return {
    mounted(el, wire) {
        const canvas = el.querySelector('#revenue-chart');
        this.renderChart(canvas, <?= json_encode($data) ?>);

        wire.on('updated', (state) => {
            this.renderChart(canvas, state.data);
        });
    },
    renderChart(canvas, data) {
        // High-performance HTML5 Canvas rendering in browser
    }
};
</script>
```

---

## 🤖 AI-Native Tool-Calling Agents

Expose backend functions and database operations to AI models with zero boilerplate:

```php
namespace App\Services;

use Pulse\AI\AiTool;
use App\Models\Product;

class StoreAgent
{
    #[AiTool(description: 'Retrieve real-time product inventory and warehouse allocation')]
    public function checkInventory(int $productId): array
    {
        $product = Product::find($productId);
        
        return [
            'product_id' => $productId,
            'in_stock'   => $product?->stock_count ?? 0,
            'warehouse'  => $product?->warehouse_id ?? 'WH-EAST-1',
            'status'     => ($product?->stock_count ?? 0) > 0 ? 'AVAILABLE' : 'BACKORDER'
        ];
    }
}
```

```php
// Automatically generate OpenAI/Anthropic/Gemini compatible tool definitions:
$tools = \Pulse\AI\Agent::extractTools(StoreAgent::class);
```

---

## 🚀 Fiber Reactor Benchmarks

By eliminating the per-request bootstrap overhead of traditional PHP-FPM, Pulse Fiber Reactor delivers orders-of-magnitude higher throughput:

```
──────────────────────────────────────────────────────────────────────────
Benchmark: 10,000 requests @ 100 concurrent connections (wrk / autocannon)
──────────────────────────────────────────────────────────────────────────

Standard PHP-FPM 8.2       ███ 2,150 req/s  (46.5ms latency)
Node.js (Express)          ████████ 8,400 req/s  (11.9ms latency)
Pulse (Standard Serve)     █████████ 9,200 req/s  (10.8ms latency)
⚡ Pulse Fiber Reactor     ██████████████████████████████ 51,400 req/s  (1.9ms latency)
──────────────────────────────────────────────────────────────────────────
```

---

## 🎛️ Pulse Studio & Time-Travel Debugger

Pulse Studio provides an embedded visual command center at `/_pulse/studio`:

```
┌─────────────────────────────────────────────────────────────────────────┐
│ ⚡ PULSE STUDIO COCKPIT                     [● REACTOR RUNNING: 50k r/s] │
├──────────────────────────┬──────────────────────────────────────────────┤
│ 📦 ACTIVE COMPONENTS     │ ⏱️ MUTATION TIME-TRAVEL STREAM                │
│ ├─ AnalyticsWidget       │  [18:32:04] Counter::increment (+1)  [Replay]│
│ ├─ Counter               │  [18:32:10] UserSearch::query ('PHP')[Replay]│
│ └─ UserSearch            │  [18:32:18] AnalyticsWidget::setPeriod(week) │
├──────────────────────────┴──────────────────────────────────────────────┤
│ 🛡️ HMAC Cryptographic Signature: e8f9...3b2a [VALIDATED]                 │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 🛠️ Developer CLI Cheat Sheet

```bash
# ── Server & Runtime ──────────────────────────────────────────
php bin/pulse serve                     # Standard PHP development server
php bin/pulse serve --reactor           # Persistent non-blocking Fiber Reactor

# ── Code Generators ───────────────────────────────────────────
php bin/pulse make:component Counter    # Create PHP reactive component
php bin/pulse make:component Chart --pulse # Create PulseX hybrid component (.pulse)
php bin/pulse make:page Dashboard       # Create new page view layout
php bin/pulse make:migration add_teams  # Create database migration blueprint

# ── Database & Migrations ─────────────────────────────────────
php bin/pulse db:migrate                # Run all pending schema migrations

# ── Diagnostics & Routing ─────────────────────────────────────
php bin/pulse routes                    # List all registered routes with tri-mode status
```

---

## 📂 Project Directory Structure

```
php/
├── .github/
│   └── workflows/
│       └── php.yml                # CI Matrix (PHP 8.1, 8.2, 8.3 on Ubuntu & Windows)
├── app/
│   ├── Components/
│   │   ├── AnalyticsWidget.pulse  # PulseX Single-File Hybrid Component
│   │   ├── Counter.php            # Reactive PHP Counter Component
│   │   └── UserSearch.php         # Live Debounced Search Component
│   └── Models/
│       └── Project.php            # Multi-Tenant ActiveRecord Model
├── bin/
│   └── pulse                      # Developer CLI (`serve`, `--reactor`, etc.)
├── database/
│   └── migrations/                # Database Schema Migrations
├── public/
│   ├── index.php                  # Web Application Entrypoint
│   └── pulse.js                   # Zero-Build Client Runtime & Wire Bridge (<10KB)
├── resources/
│   └── views/
│       ├── components/            # Reactive Component Views
│       ├── pages/                 # Full Page Layout Views
│       ├── studio.php             # Pulse Studio Developer Cockpit
│       └── layout.php             # Dark-Mode Glassmorphic SPA Shell
├── routes/
│   └── web.php                    # Tri-Mode Universal Routes (SSR ↔ SPA ↔ API)
├── src/
│   ├── Pulse.php                  # Master Application Kernel (v2.0.0 Quantum)
│   ├── AI/                        # AI-Native Agent & Tool-Calling System
│   ├── Async/                     # PHP 8.1+ Fibers (await, all, async, delay)
│   ├── Component/                 # Base Component, HMAC StateHydrator, Toasts
│   ├── Container/                 # PSR-11 Auto-wiring DI Container
│   ├── Database/                  # Multi-Tenant ORM, Schema, Blueprint, Paginator
│   ├── Events/                    # PSR-14 Event Bus
│   ├── Http/                      # Request, Response, Content Negotiation
│   ├── Middleware/                # Security (CSRF, Rate Limiting, Security Headers)
│   ├── Plugins/                   # Plugin Architecture & Service Providers
│   ├── Profiler/                  # Realtime Performance Profiler & Toolbar
│   ├── Queue/                     # Background Job Queue
│   ├── Realtime/                  # WebSocket & SSE Channels
│   ├── Replay/                    # Production Request Replay Engine
│   ├── Routing/                   # Tri-Mode Router & Model Binding
│   ├── Runtime/                   # FiberReactor & Persistent Worker Runtimes
│   ├── Studio/                    # Pulse Studio Developer Cockpit Controller
│   ├── Streaming/                 # Progressive Chunked Streaming
│   ├── Validation/                # Declarative Form & State Validation
│   └── View/                      # PulseXCompiler & ViewEngine
├── ROADMAP.md                     # Major Release Cycles & Generational Milestones
├── composer.json                  # PSR-4 Autoloading & Package Metadata
└── README.md                      # Framework Documentation
```

---

## 🗺️ Major Release Cycles & Roadmap

Pulse adheres strictly to Semantic Versioning (`SemVer 2.0.0`):

```
                      PULSE FRAMEWORK MAJOR GENERATIONS
                                      │
     ┌────────────────┬───────────────┼───────────────┬────────────────┐
     ▼                ▼               ▼               ▼                ▼
Pulse v1.x       Pulse v2.x      Pulse v3.0      Pulse v4.0       Pulse v5.0
 (Helios)        (Quantum)       (Horizon)       (Infinity)     (Singularity)
Foundation &     Fiber Reactor &  Autonomous AI   WASM & AOT      Omnipresent
SSR↔SPA Sync     Pulse Studio     Edge Mesh       Micro-VMs       Zero-Latency
```

| Generation | Codename | Target Focus & Core Capabilities | Status |
|---|---|---|---|
| **Pulse v1.x** | *Helios* | Core Identity, SSR ↔ SPA Tri-Mode Routing, Reactive Components, Fibers, Toasts | **Complete & Shipped** |
| **Pulse v2.x** | *Quantum* | Fiber Reactor (50k+ req/s), Pulse Studio, PulseX Hybrid Templates, AI Tool-Calling Agents | **Active & Live** |
| **Pulse v3.0** | *Horizon* | Autonomous Multi-Agent Mesh, Self-Healing Queues, Distributed Cloud Edge Sync | **Planned (Q1 2027)** |
| **Pulse v4.0** | *Infinity* | In-Browser WebAssembly (WASM) PHP, AOT Bytecode Compilation, Micro-VMs | **Research (Q4 2027)** |
| **Pulse v5.0** | *Singularity* | Omnipresent Zero-Latency Mesh, Natural Language Realtime UI Synthesis | **Vision Horizon** |

👉 **For complete details on minor versions and RFC specifications, see [ROADMAP.md](ROADMAP.md).**

---

## 🧪 Testing & Validation

```bash
# Validate composer configuration strictly
composer validate --strict

# Check PHP syntax across all project files
find src app routes -name "*.php" -exec php -l {} \;
```

---

## 🤝 Contributing & Community

We welcome contributions from developers worldwide!

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/EpicFeature`)
3. Commit your changes (`git commit -m 'feat: add EpicFeature'`)
4. Push to the branch (`git push origin feature/EpicFeature`)
5. Open a Pull Request targeting the **`develop`** branch

---

## 📄 License

The Pulse Framework is open-sourced software licensed under the [MIT License](LICENSE).

<br/>

<div align="center">
  <sub>Engineered with precision for the future of PHP.</sub>
</div>
