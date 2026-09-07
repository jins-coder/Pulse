<?php

declare(strict_types=1);

namespace App\Components;

use Pulse\Component\Component;

class AnalyticsWidget extends Component
{
    public string $period = 'monthly';
    public array $data = [120, 240, 190, 380, 420, 510, 680];

    public function setPeriod(string $period): void
    {
        $this->period = $period;
        if ($period === 'weekly') {
            $this->data = [85, 130, 95, 210, 180, 290, 340];
        } elseif ($period === 'daily') {
            $this->data = [20, 45, 60, 55, 90, 110, 140];
        } else {
            $this->data = [120, 240, 190, 380, 420, 510, 680];
        }
    }

    public function render(): string
    {
        return view('components.analytics-widget', [
            'period' => $this->period,
            'data' => $this->data,
            'total' => array_sum($this->data),
            'avg' => round(array_sum($this->data) / count($this->data), 1),
        ]);
    }
}
