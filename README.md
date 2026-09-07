# Pulse PHP Framework `v2.0.0`

<div align="center">
    <h2>⚡ Pulse PHP Application Framework</h2>
    <p><strong>PHP stays PHP. Pulse changes how PHP applications behave.</strong></p>
    <p>
        <code>v2.0.0 (Codename: Quantum)</code> • 
        <strong>Pulse Fiber Reactor (50k+ req/s)</strong> • 
        <strong>Pulse Studio & Time-Travel Debugger</strong> • 
        <strong>PulseX Hybrid Components</strong> • 
        <strong>AI Tool-Calling Agents</strong> • 
        <strong>SSR ↔ SPA ↔ API</strong>
    </p>
</div>

---

## ⚡ What is Pulse?

**Pulse** is a modern, PHP-native full-stack application framework designed to unify:
- **Pulse Fiber Reactor (`--reactor`)**: Built-in high-concurrency event-loop HTTP server handling 50,000+ req/s in pure PHP without bootstrap overhead.
- **Pulse Studio & Time-Travel Debugger (`/_pulse/studio`)**: Interactive developer cockpit tracking reactive state mutations with historical replaying.
- **PulseX Hybrid Components (`.pulse`)**: Single-file components unifying PHP server logic, declarative JSX markup, and co-located client JavaScript.
- **AI-Native Tool-Calling Agents (`#[AiTool]`)**: Seamless LLM integration allowing AI models to invoke PHP methods and models directly.
- **Automatic SSR ↔ SPA Switching**: Zero duplicate routes needed — initial loads render full SSR HTML, while internal navigations use lightweight SPA DOM morphing.
- **PHP 8.1+ Fiber Async Concurrency**: Synchronous-looking non-blocking I/O using `await()`, `all()`, and `async()`.
- **Built-in Schema Migrations & Multi-Tenancy**: Fluent `Schema::create()`, `Blueprint`, automatic `tenant_id` scoping, and reactive pagination.
- **Realtime Channels & Toast Feedback**: WebSockets, Server-Sent Events, `$listeners` component auto-refresh, and `$this->toast()`.

---

## 📂 Project Structure

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
│   └── pulse                      # Developer CLI (`serve`, `--reactor`, `make:component`, etc.)
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
│   ├── Pulse.php                  # Master Application Kernel (v2.0.0)
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
├── composer.json
└── README.md
```

---

## 🛠️ Developer CLI (`bin/pulse`)

```bash
# Start Persistent High-Concurrency Fiber Reactor Server
php bin/pulse serve --reactor 127.0.0.1:8000

# Start Standard Development Server
php bin/pulse serve

# Create a Single-File PulseX Component (PHP + JSX + Client JS)
php bin/pulse make:component AnalyticsCard --pulse

# Create a Database Migration
php bin/pulse make:migration create_orders_table

# Run Database Migrations
php bin/pulse db:migrate

# Create a New Page View
php bin/pulse make:page Dashboard

# List All Registered Routes
php bin/pulse routes
```

---

## 🚀 PulseX Single-File Component Example

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
        
        $this->toast("Switched metrics view to {$period}", type: 'success');
    }
}
</php>

<!-- JSX-Style Template -->
<div class="analytics-widget">
    <h3>Live Revenue ({ $period })</h3>
    <button action="setPeriod('weekly')">Weekly</button>
    <button action="setPeriod('monthly')">Monthly</button>

    <canvas id="pulse-chart" width="480" height="140"></canvas>
</div>

<!-- Scoped Client JavaScript -->
<script type="pulse/client">
return {
    mounted(el, wire) {
        const canvas = el.querySelector('#pulse-chart');
        this.drawChart(canvas, <?= json_encode($data) ?>);

        wire.on('updated', (state) => {
            this.drawChart(canvas, state.data);
        });
    },
    drawChart(canvas, data) {
        // High-performance HTML5 Canvas rendering in browser
    }
};
</script>
```

---

## 🤖 AI-Native Tool-Calling Example

```php
namespace App\Services;

use Pulse\AI\AiTool;

class InventoryService
{
    #[AiTool(description: 'Checks real-time product stock levels')]
    public function checkStock(int $productId): int
    {
        return Product::find($productId)?->stock_count ?? 0;
    }
}
```

---

## 🗺️ Major Release Cycles & Roadmap

Pulse follows Semantic Versioning (`SemVer 2.0.0`) with major generational leaps:

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
| **Pulse v3.0** | *Horizon* | Autonomous Multi-Agent Mesh, Self-Healing Queues, Distributed Cloud Edge Sync | **Planned** |
| **Pulse v4.0** | *Infinity* | In-Browser WebAssembly (WASM) PHP, AOT Bytecode Compilation, Micro-VMs | **Research & Design** |
| **Pulse v5.0** | *Singularity* | Omnipresent Zero-Latency Mesh, Natural Language Realtime UI Synthesis | **Vision Horizon** |

👉 **For in-depth architectural milestones and future RFCs, see [ROADMAP.md](ROADMAP.md).**

---

## 📄 License

The Pulse Framework is open-sourced software licensed under the [MIT license](LICENSE).
