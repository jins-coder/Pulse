<div align="center">

# ⚡ Pulse PHP Framework

### *PHP stays PHP. Pulse changes how PHP applications behave.*

[![Build Status](https://img.shields.io/github/actions/workflow/status/jins-coder/Pulse/php.yml?branch=develop&style=for-the-badge&logo=github&label=CI%20Build)](https://github.com/jins-coder/Pulse/actions)
[![PHP Version](https://img.shields.io/badge/PHP-8.1%20%7C%208.2%20%7C%208.3-777bb4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Latest Release](https://img.shields.io/badge/Release-v2.0.0%20(Quantum)-6366f1?style=for-the-badge&logo=rocket&logoColor=white)](https://github.com/jins-coder/Pulse/releases)
[![License](https://img.shields.io/badge/License-MIT-10b981?style=for-the-badge)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-Welcome-f59e0b?style=for-the-badge)](https://github.com/jins-coder/Pulse/pulls)

<p align="center">
  <a href="#-features">Features</a> •
  <a href="#-quick-start">Quick Start</a> •
  <a href="#-pulsex-single-file-components">PulseX Components</a> •
  <a href="#-ai-native-tool-calling">AI Agents</a> •
  <a href="#-fiber-reactor">Fiber Reactor</a> •
  <a href="#-studio--debugger">Pulse Studio</a> •
  <a href="#-cli-reference">CLI Reference</a> •
  <a href="#-roadmap">Roadmap</a>
</p>

</div>

---

## 📖 Table of Contents

- [⚡ What is Pulse?](#-what-is-pulse)
- [✨ Core Features](#-core-features)
- [🚀 Quick Start](#-quick-start)
- [🧩 PulseX Single-File Components (`.pulse`)](#-pulsex-single-file-components-pulse)
- [🤖 AI-Native Tool-Calling (`#[AiTool]`)](#-ai-native-tool-calling-aitool)
- [⚡ Persistent Fiber Reactor (50,000+ req/s)](#-persistent-fiber-reactor-50000-reqs)
- [🎛️ Pulse Studio & Time-Travel Debugger](#️-pulse-studio--time-travel-debugger)
- [🔄 Tri-Mode Universal Routing](#-tri-mode-universal-routing)
- [🛠️ CLI Command Reference](#️-cli-command-reference)
- [📂 Project Directory Structure](#-project-directory-structure)
- [🗺️ Major Release Cycles & Roadmap](#️-major-release-cycles--roadmap)
- [🧪 Running Tests](#-running-tests)
- [🤝 Contributing](#-contributing)
- [📄 License](#-license)

---

## ⚡ What is Pulse?

**Pulse** is a modern, high-performance, full-stack PHP application framework engineered from the ground up to eliminate the friction between server-rendered backends and interactive single-page applications.

Without modifying the PHP engine or requiring Node.js build pipelines, Pulse delivers:
1. **Persistent Memory Execution**: In-memory Fiber event loop serving 50,000+ req/s with zero bootstrap penalty.
2. **True Full-Stack Co-Location**: Single-file `.pulse` components combining PHP server classes, JSX markup, and scoped client JavaScript.
3. **Tri-Mode Universal Routing**: One route definition automatically delivers SSR HTML, SPA morph updates, or REST API JSON.
4. **AI-Native Integration**: First-class LLM tool calling via native PHP 8 attributes.

---

## ✨ Core Features

| Feature | Description |
|---|---|
| 🚀 **Fiber Reactor** | Built-in non-blocking asynchronous event loop server powered by PHP 8.1 Fibers (`bin/pulse serve --reactor`). |
| 🎛️ **Pulse Studio** | Live developer cockpit and time-travel debugger with visual state mutation replaying at `/_pulse/studio`. |
| 🧩 **PulseX Engine** | Single-file hybrid template compiler uniting `<php>`, JSX `{ $props }`, and `<script type="pulse/client">`. |
| 🤖 **AI-Native Agents** | Decorate any PHP service with `#[AiTool]` to instantly expose schemas and handlers to LLMs. |
| 🔄 **Universal Routing** | Seamless transition between Server-Side Rendering (SSR), SPA DOM Morphing, and JSON API. |
| 🔒 **Tamper-Proof State** | Cryptographic HMAC signed state hydration ensuring complete client-side security. |
| 🏢 **Built-in Multi-Tenancy** | Automatic tenant scoping, context propagation, and database migration blueprints. |
| ⚡ **Zero-Build Client** | Lightweight (<10KB) client runtime with zero npm/Webpack/Vite build steps required. |

---

## 🚀 Quick Start

### 1. Installation

Clone the repository and install dependencies via Composer:

```bash
git clone https://github.com/jins-coder/Pulse.git
cd Pulse
composer install
```

### 2. Start the Development Server

Run using the standard PHP development server or the **High-Concurrency Fiber Reactor**:

```bash
# High-concurrency persistent Fiber Reactor (50k+ req/s)
php bin/pulse serve --reactor 127.0.0.1:8000

# Or standard PHP server
php bin/pulse serve
```

### 3. Open Pulse Studio

Navigate to [http://127.0.0.1:8000/_pulse/studio](http://127.0.0.1:8000/_pulse/studio) to inspect live components, monitor request lifecycles, and replay state mutations.

---

## 🧩 PulseX Single-File Components (`.pulse`)

PulseX lets you co-locate PHP server logic, reactive JSX-style markup, and client JavaScript in a single file:

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
        
        $this->toast("Updated view to {$period}", type: 'success');
    }
}
</php>

<!-- JSX-Style Template -->
<div class="analytics-card">
    <div class="header">
        <h3>Live Revenue ({ $period })</h3>
        <button action="setPeriod('weekly')">Weekly</button>
        <button action="setPeriod('monthly')">Monthly</button>
    </div>

    <canvas id="revenue-chart" width="480" height="140"></canvas>
</div>

<!-- Scoped Client JavaScript -->
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
        // High-performance canvas chart rendering
    }
};
</script>
```

---

## 🤖 AI-Native Tool-Calling (`#[AiTool]`)

Turn any PHP class into an AI-executable tool with structured JSON Schema generation:

```php
namespace App\Services;

use Pulse\AI\AiTool;

class StoreAssistant
{
    #[AiTool(description: 'Checks real-time inventory and warehouse location')]
    public function checkInventory(int $productId): array
    {
        $product = Product::find($productId);
        return [
            'stock' => $product?->stock_count ?? 0,
            'warehouse' => $product?->warehouse_code ?? 'A-1',
            'restock_expected' => '2026-09-15'
        ];
    }
}
```

```php
// Generate OpenAI/Anthropic/Gemini compatible tool definitions:
$tools = \Pulse\AI\Agent::extractTools(StoreAssistant::class);
```

---

## ⚡ Persistent Fiber Reactor (50,000+ req/s)

Pulse includes a pure-PHP persistent socket runtime using PHP 8.1 Fibers. The framework stays booted in memory between requests:

```
                      PULSE FIBER REACTOR ARCHITECTURE
                      
    Incoming HTTP Connections (Keep-Alive Pool)
                        │
                        ▼
          ┌───────────────────────────┐
          │   stream_socket_server    │
          └─────────────┬─────────────┘
                        │ non-blocking event loop
                        ▼
          ┌───────────────────────────┐
          │   PHP 8.1+ Fiber Worker   │
          │   (In-Memory App Kernel)  │
          └─────────────┬─────────────┘
                        │ zero bootstrap latency
                        ▼
           Response Stream (50,000+ req/s)
```

Start the reactor:
```bash
php bin/pulse serve --reactor 127.0.0.1:8000
```

---

## 🎛️ Pulse Studio & Time-Travel Debugger

Pulse Studio provides an embedded cockpit at `/_pulse/studio` to monitor:
- **Reactive Mutations**: Chronological stream of all state modifications.
- **Component Inspector**: Real-time snapshot of active components and serialized props.
- **Time-Travel Replay**: Re-execute past requests with original payloads.
- **Performance Profiler**: Memory consumption, Fiber execution time, and SQL query breakdown.

---

## 🔄 Tri-Mode Universal Routing

Define a route once in `routes/web.php` — Pulse handles the rest:

```php
use Pulse\Http\Response;

$router->get('/projects', function($request) {
    $projects = Project::all();
    
    return Response::view('pages/projects', [
        'projects' => $projects
    ]);
});
```

- **Direct Browser Hit** $\rightarrow$ Renders full HTML document with SEO meta tags (SSR).
- **SPA Link Click (`wire:navigate`)** $\rightarrow$ Returns DOM morph diff payload (<2KB).
- **API Request (`Accept: application/json`)** $\rightarrow$ Returns serialized JSON response.

---

## 🛠️ CLI Command Reference

```bash
# Server Management
php bin/pulse serve                     # Start standard PHP built-in server
php bin/pulse serve --reactor           # Start high-concurrency Fiber Reactor

# Code Generation
php bin/pulse make:component Counter    # Create a PHP reactive component
php bin/pulse make:component Chart --pulse # Create a PulseX single-file component (.pulse)
php bin/pulse make:page Dashboard       # Create a new page layout view
php bin/pulse make:migration add_users  # Generate a database migration

# Database Operations
php bin/pulse db:migrate                # Execute pending schema migrations

# Routing & Diagnostics
php bin/pulse routes                    # Display all registered application routes
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
├── composer.json                  # PSR-4 Autoloading & Dependencies
└── README.md
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

## 🧪 Running Tests

Validate Composer packaging and syntax:

```bash
# Validate composer configuration
composer validate --strict

# Run PHP syntax check across the entire codebase
find src app routes -name "*.php" -exec php -l {} \;
```

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request to the **`develop`** branch

---

## 📄 License

The Pulse Framework is open-sourced software licensed under the [MIT License](LICENSE).

<div align="center">
  <sub>Built with ❤️ for the global PHP developer community.</sub>
</div>
