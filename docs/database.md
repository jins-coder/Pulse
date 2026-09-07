# Database & Migrations — Pulse Documentation ⚡

- [Introduction](#introduction)
- [Defining Schema Migrations](#defining-schema-migrations)
- [Running Migrations](#running-migrations)
- [ActiveRecord Models](#activerecord-models)
- [Automatic Multi-Tenancy](#automatic-multi-tenancy)
- [Reactive Pagination](#reactive-pagination)

---

## Introduction

Pulse provides a fluent, expressive database layer with built-in migration blueprints, ActiveRecord-style query models, and automatic tenant isolation.

---

## Defining Schema Migrations

Generate a migration using the CLI:

```bash
php bin/pulse make:migration create_products_table
```

This creates a new migration file inside `database/migrations/`:

```php
use Pulse\Database\Schema;
use Pulse\Database\Blueprint;

return new class {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->tenantId(); // Automatically creates indexed tenant_id column
            $table->string('name');
            $table->string('sku')->unique();
            $table->integer('stock_count')->default(0);
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

---

## Running Migrations

Execute all pending migrations with:

```bash
php bin/pulse db:migrate
```

---

## ActiveRecord Models

Models extend `Pulse\Database\Model`:

```php
namespace App\Models;

use Pulse\Database\Model;

class Product extends Model
{
    protected string $table = 'products';
    protected array $fillable = ['name', 'sku', 'stock_count', 'price'];
}
```

### Basic CRUD Operations:

```php
// Find by ID (automatically scoped to current tenant)
$product = Product::find(1);

// Create a new record
$product = Product::create([
    'name' => 'Quantum Processor',
    'sku' => 'QP-9000',
    'stock_count' => 45,
    'price' => 799.99
]);

// Update a record
$product->update(['stock_count' => 50]);
```

---

## Automatic Multi-Tenancy

Pulse features transparent tenant isolation. When a user authenticates or a tenant subdomain is resolved, the `TenantContext` is populated:

```php
use Pulse\Database\TenantContext;

TenantContext::setTenantId(42);

// All subsequent model queries automatically append `WHERE tenant_id = 42`
$products = Product::all();
```
