# Implementation Plan — Modern PHP Application Framework

Build a unique, PHP-native application framework that keeps the existing PHP ecosystem intact while providing a modern development model unifying SSR, SPA navigation, Reactive Components, Realtime WebSockets, Async/Fibers, Persistent Runtime, and zero-build client interaction.

Each phase will be implemented as a fully functional, self-contained and testable package in its own dedicated directory (`phase-1-core-identity`, `phase-2-application-platform`, `phase-3-realtime-async`, `phase-4-advanced-platform`), along with a unified production-ready framework core.

---

## Architecture & Phase Breakdown

```
e:/afterquery/shopify/themes/php/
├── phase-1-core-identity/
│   ├── src/
│   │   ├── Http/             # Request, Response, ContentNegotiator
│   │   ├── Routing/          # Tri-Mode Smart Router (SSR ↔ SPA ↔ API), Route Model Binding
│   │   ├── Component/        # Reactive Component Engine, State Hydration, Checksums, Lifecycle
│   │   ├── View/             # Template Engine & Partial Fragment Renderer
│   │   └── Console/          # CLI Application & Generators
│   ├── public/
│   │   ├── index.php         # Entrypoint
│   │   └── runtime.js        # Zero-Build Client Runtime (SPA interceptor, DOM morphing, bind/action directives)
│   ├── app/                  # Demo App (Live Counter, Instant Search, User Dashboard)
│   ├── bin/app               # CLI executable (`php bin/app serve`, `make:component`, etc.)
│   └── composer.json
│
├── phase-2-application-platform/
│   ├── src/
│   │   ├── Container/        # PSR-11 Dependency Injection & Auto-wiring
│   │   ├── Middleware/       # Pipeline, CSRF, CORS, Rate Limiting, Security Headers
│   │   ├── Database/         # Multi-tenant ORM, Query Builder, Migrations
│   │   ├── Validation/       # Zero-boilerplate Form Validation
│   │   ├── Events/           # PSR-14 Event Bus & Sync/Async Listeners
│   │   ├── Queue/            # Background Jobs & Queue Workers
│   │   └── Plugins/          # Plugin Architecture & Service Providers
│   ├── app/                  # Multi-tenant & Secure Application Demo
│   └── bin/app
│
├── phase-3-realtime-async/
│   ├── src/
│   │   ├── Async/            # PHP 8.1+ Fiber concurrency, await(), all(), timeouts
│   │   ├── Realtime/         # WebSocket Server & SSE Adapter, Channels, Authorization
│   │   ├── Streaming/        # Progressive SSR & Streaming UI components
│   │   └── Broadcasting/     # Realtime Event Broadcasting Hub
│   ├── app/                  # Realtime Chat & Live Streaming Metrics Demo
│   └── bin/app
│
└── phase-4-advanced-platform/
    ├── src/
    │   ├── Runtime/          # Persistent Long-Lived Worker Runtime (FastCGI/Custom Worker)
    │   ├── Profiler/         # Built-in Performance Profiler (execution ms, memory, DB time, DOM diffs)
    │   ├── Replay/           # Safe Request Snapshot & Local Replay Engine
    │   └── AI/               # AI-Native Streaming Component & Provider Adapters
    ├── app/                  # End-to-End Enterprise Showcase
    └── bin/app
```

---

## User Review Required

> [!IMPORTANT]
> **PHP Runtime Environment**:
> As detected, PHP is not yet present in the system PATH. We will provide clean, standard PHP 8.1+ code with pure-PHP standard libraries, standard polyfills for testing/demonstration, and a Node/browser-based test server harness if necessary to preview the reactive client UI live in browser, alongside executable PHP CLI scripts.

---

## Proposed Implementation Details

### 1. Phase 1 — Core Identity (`phase-1-core-identity/`)
- **Tri-Mode Smart Router**: Content negotiation detects whether a request is SSR (full page shell), SPA (partial JSON payload with updated component HTML & title), or API (`application/json`).
- **Reactive Component Engine**:
  - Base `Component` class with public reactive properties.
  - Lifecycle: `mount()`, `updating($key, $val)`, `updated()`, `render()`.
  - Cryptographically signed hydration payload (`_snapshot`, `_checksum`).
  - Action dispatch endpoint `/_framework/action` executing PHP methods and returning DOM diff patches.
- **Client Runtime (`runtime.js`)**:
  - Lightweight (<12 KB) zero-build JS engine.
  - Intercepts all internal link clicks (`<a href="...">`) for SPA navigation without full page reload.
  - Form handling (`action="..."`, `method="POST"`) with seamless partial updates.
  - DOM morphing algorithm that patches elements without losing input focus, text selection, or scroll position.
  - Directives: `bind="prop"`, `action="method"`, `debounce="300ms"`, `loading="prop"`.
- **Developer CLI (`bin/app`)**:
  - `php bin/app serve`: Built-in server runner.
  - `php bin/app make:component <Name>`: Component generator.
  - `php bin/app make:page <Name>`: Page generator.
- **Interactive Showcase App**:
  - Counter with reactive increment/decrement/reset.
  - Instant Filter/Search with debounced server-side query.
  - Dynamic Form with validation and real-time state feedback.

### 2. Phase 2 — Application Platform (`phase-2-application-platform/`)
- **PSR-11 Dependency Injection Container**: Auto-wiring, singletons, interface bindings, and method injection.
- **Middleware Pipeline**: Security by default (CSRF token generation & validation, XSS escaping, CORS headers, rate limiting).
- **Multi-Tenant Database Engine**:
  - SQLite/MySQL PDO query builder.
  - Automatic tenant isolation scoping (`TenantContext::set($id)`).
- **Validation Engine**: Declarative rule validation for reactive components and form actions.
- **Unified Event Bus & Queue**:
  - `Event::dispatch(new UserRegistered($user))` with sync/queued listeners.
  - In-memory & SQLite backed worker queue.
- **Plugin System**: Modular plugins implementing `register()` and `boot()`.

### 3. Phase 3 — Realtime & Async (`phase-3-realtime-async/`)
- **PHP Fiber Async Engine**:
  - `await(callable | Fiber | Promise)` non-blocking execution wrapper.
  - `all([...tasks])` for concurrent parallel task execution.
- **Realtime Hub**:
  - SSE (Server-Sent Events) and WebSocket transport adapters.
  - Channel subscriptions (`chat.{room}`), presence, and auth guards.
  - Component reactive listeners for realtime broadcasts (`protected array $listeners = ['orderPlaced' => 'refresh']`).
- **Streaming UI**:
  - Chunked transfer encoding SSR for progressive component loading.

### 4. Phase 4 — Advanced Platform (`phase-4-advanced-platform/`)
- **Persistent Worker Runtime**:
  - Boot once, serve many requests lifecycle.
  - State isolation & memory leak prevention between request cycles.
- **Built-in Performance Profiler**:
  - Profiler toolbar overlay detailing PHP execution time, memory usage, DB query count/duration, and hydration payload size.
- **Request Replay System**:
  - Safe payload sanitizer & snapshot logger for one-click local debugging of production failures.
- **AI-Native Component**:
  - Streaming AI component `<AI prompt="..." stream />` with token-by-token client rendering and conversation state preservation.

---

## Verification Plan

### Automated Verification
- Unit & Integration test scripts for:
  - Smart Router matching and tri-mode header negotiation.
  - Component state snapshot serialization and signature verification.
  - Action dispatch and state mutation.
  - DOM morphing and event dispatching.
  - Fiber async execution & concurrent `all()` resolution.

### Manual / Browser Verification
- Launch local development server.
- Open the application in browser subagent to verify:
  1. Initial SSR page load.
  2. SPA seamless page-to-page navigation without full browser reload.
  3. Reactive state updates (counter clicking, typing in search box with debouncing).
  4. Profiler bar metrics display.
