<?php

declare(strict_types=1);

namespace Pulse\Profiler;

class PerformanceProfiler
{
    protected float $startTime;
    protected int $startMemory;
    protected array $dbQueries = [];
    protected int $renderedComponents = 0;

    public function __construct()
    {
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage();
    }

    public function recordQuery(string $sql, float $timeMs): void
    {
        $this->dbQueries[] = ['sql' => $sql, 'time_ms' => $timeMs];
    }

    public function recordComponent(): void
    {
        $this->renderedComponents++;
    }

    public function getMetrics(): array
    {
        $durationMs = (microtime(true) - $this->startTime) * 1000;
        $memoryMb = (memory_get_usage() - $this->startMemory) / 1024 / 1024;
        $dbTotalTime = array_sum(array_column($this->dbQueries, 'time_ms'));

        return [
            'execution_time_ms' => round($durationMs, 2),
            'memory_used_mb' => round(max(0.1, $memoryMb), 2),
            'db_query_count' => count($this->dbQueries),
            'db_total_time_ms' => round($dbTotalTime, 2),
            'components_rendered' => $this->renderedComponents,
        ];
    }

    public function renderToolbarHtml(): string
    {
        $m = $this->getMetrics();
        return <<<HTML
<div id="pulse-profiler" style="position:fixed;bottom:0;left:0;right:0;background:rgba(9,13,22,0.92);backdrop-filter:blur(10px);border-top:1px solid rgba(56,189,248,0.3);color:#f8fafc;font-family:'JetBrains Mono',monospace;font-size:11px;padding:6px 16px;display:flex;gap:22px;align-items:center;z-index:99999;box-shadow:0 -4px 20px rgba(0,0,0,0.5);">
    <div style="font-weight:bold;color:#38bdf8;display:flex;align-items:center;gap:6px;">
        <svg style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
        <span>PULSE v1.0.0</span>
    </div>
    <div style="display:flex;align-items:center;gap:5px;">
        <svg style="width:13px;height:13px;stroke:#4ade80;fill:none;stroke-width:2;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        <span>Time: <strong style="color:#4ade80;">{$m['execution_time_ms']} ms</strong></span>
    </div>
    <div style="display:flex;align-items:center;gap:5px;">
        <svg style="width:13px;height:13px;stroke:#a78bfa;fill:none;stroke-width:2;" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
        <span>Memory: <strong style="color:#a78bfa;">{$m['memory_used_mb']} MB</strong></span>
    </div>
    <div style="display:flex;align-items:center;gap:5px;">
        <svg style="width:13px;height:13px;stroke:#facc15;fill:none;stroke-width:2;" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
        <span>DB: <strong style="color:#facc15;">{$m['db_query_count']} queries ({$m['db_total_time_ms']} ms)</strong></span>
    </div>
    <div style="display:flex;align-items:center;gap:5px;">
        <svg style="width:13px;height:13px;stroke:#38bdf8;fill:none;stroke-width:2;" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
        <span>Components: <strong style="color:#38bdf8;">{$m['components_rendered']}</strong></span>
    </div>
</div>
HTML;
    }
}
