# PulseX Single-File Components — Pulse Documentation ⚡

- [Introduction](#introduction)
- [Writing a Single-File Component (`.pulse`)](#writing-a-single-file-component-pulse)
- [Reactive State & Public Properties](#reactive-state--public-properties)
- [Actions & Event Handlers](#actions--event-handlers)
- [Cryptographic HMAC State Hydration](#cryptographic-hmac-state-hydration)
- [Toast Notifications & Server Feedback](#toast-notifications--server-feedback)
- [Scoped Client JavaScript (`wire`)](#scoped-client-javascript-wire)

---

## Introduction

Pulse introduces **PulseX (`.pulse`)**, a single-file component architecture combining the best of server-side PHP business logic, declarative JSX-style markup, and co-located browser JavaScript.

No Webpack, Vite, or Babel build step is required — the `PulseXCompiler` compiles components on the fly into high-efficiency PHP render trees.

---

## Writing a Single-File Component (`.pulse`)

Components live in `app/Components/`:

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

<!-- JSX-Style Declarative Template -->
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

## Reactive State & Public Properties

Every public property declared on your component class is automatically serialized and synchronized between the server and the browser:

```php
class Counter extends Component
{
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }

    public function decrement(): void
    {
        $this->count--;
    }
}
```

Rendering in any view:

```php
<?= component(\App\Components\Counter::class, ['count' => 10]) ?>
```

---

## Cryptographic HMAC State Hydration

Whenever a component mutates, Pulse transmits an encrypted and signed HMAC payload containing the component's state. When the client sends an action, Pulse verifies the HMAC signature using `APP_KEY`.

If any client-side tampering is detected, the request is immediately rejected before code execution.
