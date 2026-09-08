<?php

declare(strict_types=1);

namespace App\Models;

/**
 * SaaS Multi-Tenant Subscription Model for Pulse Framework v3.0 (Horizon).
 */
class Subscription
{
    public const TIER_STARTER = 'starter';
    public const TIER_PRO = 'pro';
    public const TIER_ENTERPRISE = 'enterprise';
    public const TIER_HORIZON = 'horizon';

    public static function getTiers(): array
    {
        return [
            self::TIER_STARTER => [
                'id' => self::TIER_STARTER,
                'name' => 'Starter (Community)',
                'tagline' => 'Essential foundation for single-server apps & side projects',
                'monthly_price' => 0,
                'annual_price' => 0,
                'badge' => 'Free Forever',
                'color' => '#94a3b8',
                'limits' => [
                    'agent_runs' => '50 / mo',
                    'queue_jobs' => '5,000 / mo',
                    'fiber_concurrency' => '1,000 req/s',
                    'edge_nodes' => '1 Node',
                    'telemetry_retention' => '24 Hours',
                ],
                'features' => [
                    'Fiber Reactor Core (50k req/s)' => true,
                    'PulseX Single-File Components' => true,
                    'Zero-Build SPA Navigation' => true,
                    'Autonomous Agent Mesh' => false,
                    'Self-Healing DLQ Queues' => false,
                    'Distributed CRDT Edge Sync' => false,
                    'Full OpenTelemetry Tracing' => false,
                ],
            ],
            self::TIER_PRO => [
                'id' => self::TIER_PRO,
                'name' => 'Pro Growth',
                'tagline' => 'High-throughput scaling for growing SaaS startups',
                'monthly_price' => 29,
                'annual_price' => 24, // $288 billed annually
                'badge' => 'Most Popular',
                'color' => '#38bdf8',
                'limits' => [
                    'agent_runs' => '5,000 / mo',
                    'queue_jobs' => '100,000 / mo',
                    'fiber_concurrency' => '15,000 req/s',
                    'edge_nodes' => '5 Edge POPs',
                    'telemetry_retention' => '30 Days',
                ],
                'features' => [
                    'Fiber Reactor Core (50k req/s)' => true,
                    'PulseX Single-File Components' => true,
                    'Zero-Build SPA Navigation' => true,
                    'Autonomous Agent Mesh' => true,
                    'Self-Healing DLQ Queues' => true,
                    'Distributed CRDT Edge Sync' => false,
                    'Full OpenTelemetry Tracing' => true,
                ],
            ],
            self::TIER_ENTERPRISE => [
                'id' => self::TIER_ENTERPRISE,
                'name' => 'Enterprise Scale',
                'tagline' => 'Distributed edge clustering with zero-downtime persistence',
                'monthly_price' => 99,
                'annual_price' => 79,
                'badge' => 'High Scale',
                'color' => '#818cf8',
                'limits' => [
                    'agent_runs' => '50,000 / mo',
                    'queue_jobs' => '2,000,000 / mo',
                    'fiber_concurrency' => '50,000+ req/s',
                    'edge_nodes' => 'Unlimited Edge POPs',
                    'telemetry_retention' => '90 Days',
                ],
                'features' => [
                    'Fiber Reactor Core (50k req/s)' => true,
                    'PulseX Single-File Components' => true,
                    'Zero-Build SPA Navigation' => true,
                    'Autonomous Agent Mesh' => true,
                    'Self-Healing DLQ Queues' => true,
                    'Distributed CRDT Edge Sync' => true,
                    'Full OpenTelemetry Tracing' => true,
                ],
            ],
            self::TIER_HORIZON => [
                'id' => self::TIER_HORIZON,
                'name' => 'Horizon AI Quantum',
                'tagline' => 'Autonomous self-driving infrastructure & unlimited AI agents',
                'monthly_price' => 249,
                'annual_price' => 199,
                'badge' => '⚡ v3.0 Ultimate',
                'color' => '#f43f5e',
                'limits' => [
                    'agent_runs' => 'Unlimited Swarms',
                    'queue_jobs' => 'Unlimited Persistent',
                    'fiber_concurrency' => 'Sub-millisecond Micro-VMs',
                    'edge_nodes' => 'Global Omnipresent Mesh',
                    'telemetry_retention' => '365 Days + AI Diagnosis',
                ],
                'features' => [
                    'Fiber Reactor Core (50k req/s)' => true,
                    'PulseX Single-File Components' => true,
                    'Zero-Build SPA Navigation' => true,
                    'Autonomous Agent Mesh' => true,
                    'Self-Healing DLQ Queues' => true,
                    'Distributed CRDT Edge Sync' => true,
                    'Full OpenTelemetry Tracing' => true,
                ],
            ],
        ];
    }

    public static function canAccessFeature(string $currentTier, string $featureKey): bool
    {
        $tiers = self::getTiers();
        return $tiers[$currentTier]['features'][$featureKey] ?? false;
    }
}
