# Artisan-Style Developer CLI (`bin/pulse`) — Pulse Documentation ⚡

- [Overview](#overview)
- [Available Commands](#available-commands)
- [Server Commands](#server-commands)
- [Code Generation (`make:`)](#code-generation-make)
- [Database Migrations (`db:`)](#database-migrations-db)
- [Routing Diagnostics](#routing-diagnostics)

---

## Overview

Pulse includes an expressive Artisan-style command-line interface located at `bin/pulse`.

To view all available commands:

```bash
php bin/pulse
```

---

## Available Commands

```
Pulse Framework CLI v2.0.0 (Codename: Quantum)

Usage:
  php bin/pulse <command> [arguments] [options]

Server:
  serve [--reactor] [host:port]   Start the built-in server or persistent Fiber Reactor

Make Generators:
  make:component <Name> [--pulse] Generate a reactive component or PulseX (.pulse) file
  make:page <Name>                Generate a new page layout view template
  make:migration <Name>           Generate a database schema migration

Database:
  db:migrate                      Execute all outstanding database migrations

Routing & Diagnostics:
  routes                          List all registered application routes
  version                         Display the framework version and runtime info
```

---

## Server Commands

### High-Concurrency Fiber Reactor
```bash
php bin/pulse serve --reactor 127.0.0.1:8000
```
Runs the non-blocking event-loop reactor for 50,000+ requests per second.

### Standard PHP Development Server
```bash
php bin/pulse serve 127.0.0.1:8000
```

---

## Code Generation (`make:`)

```bash
# Create a standard PHP Reactive Component
php bin/pulse make:component StatsCard

# Create a Single-File PulseX Component (.pulse)
php bin/pulse make:component AnalyticsWidget --pulse

# Create a Page View
php bin/pulse make:page Billing

# Create a Database Migration
php bin/pulse make:migration create_subscriptions_table
```
