# Pulse PHP Framework `v1.0.0`

<div align="center">
    <h2>⚡ Pulse PHP Application Framework</h2>
    <p><strong>PHP stays PHP. Pulse changes how PHP applications behave.</strong></p>
    <p>
        <code>v1.0.0 (Codename: Helios)</code> • 
        <strong>SSR ↔ SPA ↔ API</strong> • 
        <strong>Reactive Components</strong> • 
        <strong>Fiber Async</strong> • 
        <strong>Realtime WebSockets</strong> • 
        <strong>Multi-Tenancy ORM</strong>
    </p>
</div>

---

## ⚡ What is Pulse?

Pulse is a modern, PHP-native full-stack application framework designed to unify:
- **Server-Side Rendering (SSR)** for instantaneous initial page loads and SEO.
- **Single-Page Application (SPA)** client pushState navigation without page reloads.
- **Native Reactive PHP Components** with cryptographically signed HMAC state.
- **PHP 8.1+ Fiber Async Concurrency** (`await()`, `all()`, `async()`, `delay()`).
- **Realtime Channels & Progressive Streaming** (WebSockets / Server-Sent Events).
- **Multi-Tenant Database ORM** with automated tenant scoping.
- **Security Defaults** (CSRF protection, Rate Limiting, Security Headers).
- **Persistent Worker Runtime** and embedded microsecond performance profiler.

---

## 📂 Project Structure

```
php/
├── app/
│   ├── Components/
│   │   ├── Counter.php            # Reactive PHP Counter Component
│   │   └── UserSearch.php         # Live Debounced Search Component
│   └── Models/
│       └── Project.php            # Multi-Tenant ActiveRecord Model
├── bin/
│   └── pulse                      # Developer CLI (`php bin/pulse serve`, `make:component`, etc.)
├── public/
│   ├── index.php                  # Web Application Entrypoint
│   └── pulse.js                   # Zero-Build Client Runtime (<10KB)
├── resources/
│   └── views/
│       ├── components/            # Reactive Component Views
│       ├── pages/                 # Full Page Layout Views
│       └── layout.php             # Dark-Mode Glassmorphic SPA Shell
├── routes/
│   └── web.php                    # Tri-Mode Universal Routes (SSR ↔ SPA ↔ API)
├── src/
│   ├── Pulse.php                  # Master Application Kernel (v1.0.0)
│   ├── AI/                        # AI-Native Streaming LLM Integration
│   ├── Async/                     # PHP 8.1+ Fibers (await, all, async, delay)
│   ├── Component/                 # Base Component & HMAC StateHydrator
│   ├── Container/                 # PSR-11 Auto-wiring DI Container
│   ├── Database/                  # Multi-Tenant ORM, QueryBuilder, Connection
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
│   └── View/                      # ViewEngine & Partial Fragment Renderer
├── composer.json
└── README.md
```

---

## 🛠️ Developer CLI (`bin/pulse`)

```bash
# Start Pulse development server
php bin/pulse serve

# Create a Reactive PHP component and its view template
php bin/pulse make:component TeamDirectory

# Create a new Page View
php bin/pulse make:page Dashboard

# Create a Multi-Tenant Model
php bin/pulse make:model Customer

# List all registered routes
php bin/pulse routes
```

---

## 🚀 Reactive Component Example

```php
namespace App\Components;

use Pulse\Component\Component;

class Counter extends Component
{
    public int $count = 0;
    public int $step = 1;

    public function increment(): void
    {
        $this->count += $this->step;
    }

    public function decrement(): void
    {
        $this->count -= $this->step;
    }

    public function render(): string
    {
        return view('components.counter', [
            'count' => $this->count,
            'step' => $this->step,
        ]);
    }
}
```

```html
<!-- resources/views/components/counter.php -->
<div class="counter-component">
    <h1><?= e($count) ?></h1>
    <button action="decrement">-</button>
    <button action="increment">+</button>
    <input type="number" bind="step" value="<?= e($step) ?>">
</div>
```
