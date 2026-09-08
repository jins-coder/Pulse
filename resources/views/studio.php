<?php $this->extends('layout'); ?>

<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 2rem 1rem;">
    <!-- Studio Header -->
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 2rem; background: rgba(21, 13, 36, 0.85); padding: 1.5rem 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border);">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #a855f7, #6366f1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #ffffff; box-shadow: 0 0 25px rgba(168, 85, 247, 0.6);">
                <svg class="icon" style="stroke-width: 2.5; stroke: #ffffff; width: 24px; height: 24px;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            </div>
            <div>
                <h1 style="font-size: 1.6rem; font-weight: 800; display: flex; align-items: center; gap: 0.6rem; margin: 0;">
                    <span>PULSE STUDIO</span>
                    <span class="badge" style="background: rgba(168, 85, 247, 0.15); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.3);">v<?= e($version) ?> • <?= e($codename) ?></span>
                </h1>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0.2rem 0 0 0;">Autonomous AI Swarms • OpenTelemetry Tracing • Self-Healing Queues • CRDT Edge Sync</p>
            </div>
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center;">
            <span class="badge" style="background: rgba(74, 222, 128, 0.15); color: #4ade80; padding: 0.4rem 0.8rem; font-size: 0.8rem; border: 1px solid rgba(74, 222, 128, 0.3);">
                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #4ade80; margin-right: 4px;"></span>
                Fiber Reactor Active (50k+ req/s)
            </span>
            <a href="/upgrade" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.4rem 0.9rem;">⚡ Upgrade Quotas</a>
            <a href="/" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.4rem 0.9rem;">← Back to App</a>
        </div>
    </div>

    <!-- 5-Panel Studio Cockpit Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 1.75rem;">
        
        <!-- Panel 1: AI Multi-Agent Mesh Monitor -->
        <div class="card" style="border: 1px solid rgba(168, 85, 247, 0.25);">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon" style="background: rgba(168, 85, 247, 0.2); color: #c084fc;">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M12 2a8 8 0 0 0-8 8c0 3.36 2.07 6.24 5 7.42V20a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2.58c2.93-1.18 5-4.06 5-7.42a8 8 0 0 0-8-8z"></path></svg>
                    </div>
                    <span>AI Multi-Agent Mesh Monitor</span>
                </div>
                <span class="badge" style="background: rgba(168, 85, 247, 0.15); color: #c084fc;">v3.0 Mesh</span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
                Active autonomous agent workers running concurrent Fiber coroutines:
            </p>

            <div style="display: flex; flex-direction: column; gap: 0.5rem; background: rgba(0,0,0,0.35); padding: 0.75rem; border-radius: var(--radius-md);">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(168, 85, 247, 0.1); border-left: 3px solid #a855f7; border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem; color: #c084fc;">Architect Agent (Supervisor)</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Decomposition & Schema Synthesis</div>
                    </div>
                    <span class="badge" style="font-size: 0.7rem; background: rgba(34, 197, 94, 0.2); color: #4ade80;">Running</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255, 255, 255, 0.02); border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem; color: #fff;">Code Synthesizer</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">PulseX Single-File Compilation</div>
                    </div>
                    <span class="badge" style="font-size: 0.7rem; background: rgba(56, 189, 248, 0.2); color: #38bdf8;">Idle</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255, 255, 255, 0.02); border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem; color: #fff;">QA & Security Verifier</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Cryptographic HMAC Signature Verification</div>
                    </div>
                    <span class="badge" style="font-size: 0.7rem; background: rgba(56, 189, 248, 0.2); color: #38bdf8;">Idle</span>
                </div>
            </div>
            <div style="margin-top: 0.75rem; text-align: right;">
                <a href="/agents" class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.3rem 0.6rem;">Open Swarm Visualizer →</a>
            </div>
        </div>

        <!-- Panel 2: Self-Healing Queue & DLQ Cockpit -->
        <div class="card" style="border: 1px solid rgba(244, 63, 94, 0.25);">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon" style="background: rgba(244, 63, 94, 0.2); color: #fb7185;">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                    </div>
                    <span>Self-Healing Queue & DLQ</span>
                </div>
                <span class="badge" style="background: rgba(244, 63, 94, 0.15); color: #fb7185;">Auto-Heal</span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
                Intelligent dead-letter queue classification and automated jitter recovery:
            </p>

            <div style="display: flex; flex-direction: column; gap: 0.5rem; background: rgba(0,0,0,0.35); padding: 0.75rem; border-radius: var(--radius-md);">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(34, 197, 94, 0.1); border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem; color: #4ade80;">Auto-Healed Jobs</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Network Transient & Timeout Adaptations</div>
                    </div>
                    <span style="font-family: 'JetBrains Mono'; font-weight: 800; font-size: 1.1rem; color: #4ade80;">142</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255,255,255,0.02); border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem; color: #fff;">Dead Letter Queue (DLQ)</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">0 unhandled exceptions</div>
                    </div>
                    <span class="badge" style="font-size: 0.7rem; background: rgba(34, 197, 94, 0.2); color: #4ade80;">Healthy</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255,255,255,0.02); border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem; color: #fff;">Active Strategies</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Exponential Backoff, Circuit Breaker</div>
                    </div>
                    <span class="badge" style="font-size: 0.7rem;">Active</span>
                </div>
            </div>
        </div>

        <!-- Panel 3: OpenTelemetry Distributed Tracing -->
        <div class="card" style="border: 1px solid rgba(56, 189, 248, 0.25);">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon" style="background: rgba(56, 189, 248, 0.2); color: #38bdf8;">
                        <svg class="icon" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    </div>
                    <span>OpenTelemetry Distributed Tracing</span>
                </div>
                <span class="badge" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8;">W3C Spans</span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
                Microsecond waterfall span trees for Fiber coroutines and DB queries:
            </p>

            <div style="display: flex; flex-direction: column; gap: 0.5rem; background: rgba(0,0,0,0.35); padding: 0.75rem; border-radius: var(--radius-md);">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(56, 189, 248, 0.1); border-left: 3px solid #38bdf8; border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem; color: #38bdf8;">http.request /upgrade</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Span: 0.62ms • HTTP 200 OK</div>
                    </div>
                    <span style="font-family: 'JetBrains Mono'; font-size: 0.75rem; color: #38bdf8;">0.62ms</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255,255,255,0.02); margin-left: 1rem; border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.82rem; color: #e2e8f0;">component.hydrate SubscriptionUpgrade</div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">HMAC verify + state injection</div>
                    </div>
                    <span style="font-family: 'JetBrains Mono'; font-size: 0.75rem; color: #94a3b8;">0.18ms</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255,255,255,0.02); margin-left: 1rem; border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.82rem; color: #e2e8f0;">db.query SELECT * FROM subscriptions</div>
                        <div style="font-size: 0.72rem; color: var(--text-muted);">TenantContext: 'default'</div>
                    </div>
                    <span style="font-family: 'JetBrains Mono'; font-size: 0.75rem; color: #94a3b8;">0.09ms</span>
                </div>
            </div>
        </div>

        <!-- Panel 4: Distributed CRDT Edge State Sync -->
        <div class="card" style="border: 1px solid rgba(250, 204, 21, 0.25);">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon" style="background: rgba(250, 204, 21, 0.2); color: #facc15;">
                        <svg class="icon" viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                    </div>
                    <span>Distributed CRDT Edge Sync</span>
                </div>
                <span class="badge" style="background: rgba(250, 204, 21, 0.15); color: #facc15;">LWW / PN-Counter</span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
                Conflict-free state replication across global edge clusters:
            </p>

            <div style="display: flex; flex-direction: column; gap: 0.5rem; background: rgba(0,0,0,0.35); padding: 0.75rem; border-radius: var(--radius-md);">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255,255,255,0.02); border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem; color: #fff;">Edge Node Cluster</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">node_fra, node_sfo, node_tyo</div>
                    </div>
                    <span class="badge" style="font-size: 0.7rem; background: rgba(34, 197, 94, 0.2); color: #4ade80;">Synced (0ms)</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255,255,255,0.02); border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem; color: #fff;">Replicated Counters</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Positive-Negative Counter convergence</div>
                    </div>
                    <span class="badge" style="font-size: 0.7rem;">PN-Converged</span>
                </div>
            </div>
        </div>

        <!-- Panel 5: Time-Travel Component State Debugger -->
        <div class="card" style="border: 1px solid rgba(255,255,255,0.1);">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon">
                        <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                    </div>
                    <span>Time-Travel State Debugger</span>
                </div>
                <span class="badge">Live Timeline</span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
                Step forward and backward through reactive component mutations:
            </p>

            <div style="display: flex; flex-direction: column; gap: 0.5rem; background: rgba(0,0,0,0.35); padding: 0.75rem; border-radius: var(--radius-md);">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(56, 189, 248, 0.12); border-left: 3px solid #38bdf8; border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem; color: #38bdf8;">State #04 • upgradePlan()</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">App\Components\SubscriptionUpgrade • 0.45ms</div>
                    </div>
                    <button class="btn btn-secondary" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">Replay</button>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255, 255, 255, 0.02); border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem;">State #03 • setPeriod('annual')</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">App\Components\SubscriptionUpgrade • 0.22ms</div>
                    </div>
                    <button class="btn btn-secondary" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">Replay</button>
                </div>
            </div>
        </div>

    </div>
</div>
