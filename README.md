# Pulse PHP Framework `v1.4.0`

<div align="center">
    <h2>⚡ Pulse PHP Application Framework</h2>
    <p><strong>PHP stays PHP. Pulse changes how PHP applications behave.</strong></p>
    <p>
        <code>v1.4.0 (Codename: Helios)</code> • 
        <strong>SSR ↔ SPA ↔ API</strong> • 
        <strong>PulseX Hybrid Components</strong> • 
        <strong>Schema Migrations</strong> • 
        <strong>Realtime Toasts</strong> • 
        <strong>Observability Studio</strong>
    </p>
</div>

---

## ⚡ What is Pulse?

Pulse is a modern, PHP-native full-stack application framework designed to unify:
- **Server-Side Rendering (SSR)** for instantaneous initial page loads and SEO.
- **Single-Page Application (SPA)** client pushState navigation without page reloads.
- **PulseX Single-File Components (`.pulse`)** unifying PHP server logic, JSX markup, and co-located client JS.
- **PHP 8.1+ Fiber Async Concurrency** (`await()`, `all()`, `async()`, `delay()`).
- **Realtime Channels & Toast Feedback** (WebSockets, SSE, `$this->toast()`, `$listeners`).
- **Multi-Tenant Database ORM & Migrations** (`Schema::create()`, `Blueprint`, automatic tenant scoping).
- **Security Defaults** (CSRF protection, Rate Limiting, Security Headers).
- **Persistent Worker Runtime & Request Replay Studio** (`/_pulse/replay`).

---

## 📂 Project Structure

```
php/
├── .github/
│   └── workflows/
│       └── php.yml                # CI Matrix (PHP 8.1, 8.2, 8.3 on Ubuntu/Windows)
├── app/
│   ├── Components/
│   │   ├── AnalyticsWidget.pulse  # PulseX Single-File Hybrid Component
│   │   ├── Counter.php            # Reactive PHP Counter Component
│   │   └── UserSearch.php         # Live Debounced Search Component
│   └── Models/
│       └── Project.php            # Multi-Tenant ActiveRecord Model
├── bin/
│   └── pulse                      # Developer CLI (`serve`, `make:component`, `db:migrate`, etc.)
├── database/
│   └── migrations/                # Database Schema Migrations
├── public/
│   ├── index.php                  # Web Application Entrypoint
│   └── pulse.js                   # Zero-Build Client Runtime & Wire Bridge (<10KB)
├── resources/
│   └── views/
│       ├── components/            # Reactive Component Views
│       ├── pages/                 # Full Page Layout Views
│       └── layout.php             # Dark-Mode Glassmorphic SPA Shell
├── routes/
│   └── web.php                    # Tri-Mode Universal Routes (SSR ↔ SPA ↔ API)
├── src/
│   ├── Pulse.php                  # Master Application Kernel (v1.4.0)
│   ├── AI/                        # AI-Native Streaming LLM Integration
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
│   ├── Runtime/                   # Persistent Worker Runtime
│   ├── Streaming/                 # Progressive Chunked Streaming
│   ├── Validation/                # Declarative Form & State Validation
│   └── View/                      # PulseXCompiler & ViewEngine
├── composer.json
└── README.md
```

---

## 🛠️ Developer CLI (`bin/pulse`)

```bash
# Start Pulse development server
php bin/pulse serve

# Create a Single-File PulseX Component (PHP + JSX + Client JS)
php bin/pulse make:component AnalyticsCard --pulse

# Create a Database Migration
php bin/pulse make:migration create_orders_table

# Run Database Migrations
php bin/pulse db:migrate

# Create a new Page View
php bin/pulse make:page Dashboard

# List all registered routes
php bin/pulse routes
```
