# Pulse Framework Documentation ⚡

Welcome to the official **Pulse PHP Framework** documentation — crafted in the clear, developer-first style of modern web frameworks.

```
  ____       _             ____  _   _ ____  
 |  _ \ _   _| |___  ___   |  _ \| | | |  _ \ 
 | |_) | | | | / __|/ _ \  | |_) | |_| | |_) |
 |  __/| |_| | \__ \  __/  |  __/|  _  |  __/ 
 |_|    \__,_|_|___/\___|  |_|   |_| |_|_|    
```

---

## 📚 Table of Contents

### 1. Getting Started
- [**Installation & Architecture**](index.md#installation--architecture)
- [**Directory Structure**](index.md#directory-structure)
- [**Configuration & Environment**](index.md#configuration)

### 2. Architecture & Core Concepts
- [**Tri-Mode Universal Routing**](routing.md) — Seamless SSR ↔ SPA ↔ REST API in a single endpoint.
- [**PulseX Single-File Components**](components.md) — Co-located PHP `<php>`, JSX markup, and client `<script>`.
- [**Fiber Reactor & Concurrency**](fiber-reactor.md) — In-memory 50,000+ req/s event loop socket server.
- [**Database, Migrations & Multi-Tenancy**](database.md) — Fluent `Schema::create()`, `Blueprint`, and automatic tenant scoping.
- [**AI Tool-Calling Agents**](ai-agents.md) — Expose PHP methods to LLMs with `#[AiTool]`.
- [**Artisan-Style Developer CLI**](artisan-cli.md) — Code generators, migrations, and server management with `bin/pulse`.

---

## 🚀 Installation & Architecture

### System Requirements
Pulse requires standard PHP 8.1 or higher with zero custom extensions or engine modifications:
- PHP >= 8.1 (PHP 8.2 & 8.3 fully supported)
- Extensions: `mbstring`, `pdo`, `json`, `sockets`
- Composer 2.x

### Quick Installation

```bash
# Clone the repository
git clone https://github.com/jins-coder/Pulse.git my-pulse-app
cd my-pulse-app

# Install dependencies via Composer
composer install

# Start the High-Concurrency Fiber Reactor
php bin/pulse serve --reactor 127.0.0.1:8000
```

---

## 📂 Directory Structure

```
my-pulse-app/
├── app/
│   ├── Components/        # Reactive PHP & PulseX (.pulse) components
│   └── Models/            # Multi-tenant ActiveRecord models
├── bin/
│   └── pulse              # Artisan-style developer CLI
├── database/
│   └── migrations/        # Database schema migrations
├── public/
│   ├── favicon.svg        # Neon brand favicon
│   ├── assets/            # Vector logos and brand assets
│   ├── index.php          # Web application entry point
│   └── pulse.js           # Zero-build client runtime (<10KB)
├── resources/
│   └── views/             # Page templates, studio cockpit, and layouts
├── routes/
│   └── web.php            # Universal tri-mode route definitions
├── src/                   # Pulse Framework Core Engine
├── ROADMAP.md             # Generational release milestones
└── composer.json          # Autoloading configuration
```

---

## 💡 The Pulse Philosophy

In standard PHP, every HTTP request triggers a cold bootstrap: autoloaders fire, configuration is parsed, dependency injection graphs are constructed, and database connections are established from scratch — only to be torn down milliseconds later.

**Pulse changes this with two foundational innovations:**
1. **Fiber Reactor**: Keeps the framework booted in persistent memory, reducing latency to under 2ms.
2. **PulseX & Wire Bridge**: Lets PHP server components manage interactive frontend state over WebSocket/SSE/HTTP without requiring heavy client-side JavaScript frameworks.
