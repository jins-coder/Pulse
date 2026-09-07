<?php

declare(strict_types=1);

namespace App\Components;

use Pulse\Component\Component;

class Counter extends Component
{
    public int $count = 0;
    public int $step = 1;

    public function mount(int $initial = 0): void
    {
        $this->count = $initial;
    }

    public function increment(): void
    {
        $this->count += $this->step;
    }

    public function decrement(): void
    {
        $this->count -= $this->step;
    }

    public function resetCount(): void
    {
        $this->count = 0;
    }

    public function render(): string
    {
        return view('components.counter', [
            'count' => $this->count,
            'step' => $this->step,
        ]);
    }
}
