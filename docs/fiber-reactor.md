# Fiber Reactor Server — Pulse Documentation ⚡

- [Architecture Overview](#architecture-overview)
- [Starting the Reactor](#starting-the-reactor)
- [Fiber Async Concurrency Helpers](#fiber-async-concurrency-helpers)
- [Performance Benchmarks](#performance-benchmarks)
- [Deployment & Production Tuning](#deployment--production-tuning)

---

## Architecture Overview

Traditional PHP applications operate on a "share-nothing" model where PHP-FPM spawns a new process, parses files, loads framework providers, and destroys memory on every HTTP request.

The **Pulse Fiber Reactor** (`src/Runtime/FiberReactor.php`) boots the entire application kernel into memory once and manages non-blocking HTTP socket requests using PHP 8.1+ Fibers.

```
                      PULSE FIBER REACTOR PIPELINE
                      
    Incoming HTTP Clients (Keep-Alive Pool)
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
                        │ sub-millisecond response
                        ▼
           HTTP Response Stream (50,000+ req/s)
```

---

## Starting the Reactor

To run the persistent reactor:

```bash
php bin/pulse serve --reactor 127.0.0.1:8000
```

---

## Fiber Async Concurrency Helpers

Pulse provides intuitive asynchronous utilities inside `Pulse\Async\Async`:

```php
use function Pulse\Async\await;
use function Pulse\Async\all;
use function Pulse\Async\async;

// Run tasks concurrently and await results
$results = all([
    fn() => fetchExternalPricing(),
    fn() => loadCustomerHistory(),
    fn() => queryInventoryCache(),
]);
```
