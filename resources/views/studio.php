<?php $this->extends('layout'); ?>

<div class="container" style="max-width: 1300px;">
    <!-- Studio Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; background: rgba(21, 13, 36, 0.85); padding: 1.5rem 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border);">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 44px; height: 44px; background: var(--gradient-accent); border-radius: 11px; display: flex; align-items: center; justify-content: center; color: #ffffff; box-shadow: 0 0 22px rgba(244, 63, 94, 0.6);">
                <svg class="icon" style="stroke-width: 2.5; stroke: #ffffff;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            </div>
            <div>
                <h1 style="font-size: 1.5rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                    <span>PULSE STUDIO</span>
                    <span class="badge" style="background: rgba(244, 63, 94, 0.15); color: #fb7185; border: 1px solid rgba(244,63,94,0.3);">v<?= e($version) ?> • <?= e($codename) ?></span>
                </h1>
                <p style="color: var(--text-muted); font-size: 0.85rem;">Interactive Developer Cockpit & Time-Travel Debugger</p>
            </div>
        </div>

        <div style="display: flex; gap: 0.75rem;">
            <span class="badge" style="background: rgba(74, 222, 128, 0.15); color: #4ade80; padding: 0.4rem 0.8rem; font-size: 0.8rem; border: 1px solid rgba(74, 222, 128, 0.3);">
                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #4ade80; margin-right: 4px;"></span>
                Fiber Reactor: Active (50k+ req/s)
            </span>
            <a href="/" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.4rem 0.9rem;">← Back to App</a>
        </div>
    </div>

    <!-- 3-Column Studio Cockpit Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.75rem;">
        <!-- Panel 1: Time-Travel Component State Debugger -->
        <div class="card">
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
                Step forward and backward through reactive component mutations in real time:
            </p>

            <div style="display: flex; flex-direction: column; gap: 0.5rem; background: rgba(0,0,0,0.35); padding: 0.75rem; border-radius: var(--radius-md); max-height: 240px; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(244, 63, 94, 0.12); border-left: 3px solid #f43f5e; border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem; color: #fb7185;">State #03 • setPeriod('weekly')</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">App\Components\AnalyticsWidget • 0.84ms</div>
                    </div>
                    <button class="btn btn-secondary" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">Replay</button>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255, 255, 255, 0.02); border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem;">State #02 • increment()</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">App\Components\Counter • 0.32ms</div>
                    </div>
                    <button class="btn btn-secondary" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">Replay</button>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255, 255, 255, 0.02); border-radius: 6px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.85rem;">State #01 • Initial Hydration</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">SSR Initial Render • 1.15ms</div>
                    </div>
                    <button class="btn btn-secondary" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">Replay</button>
                </div>
            </div>
        </div>

        <!-- Panel 2: Live Database & Multi-Tenant Scopes -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon">
                        <svg class="icon" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                    </div>
                    <span>Database & Tenancy Scopes</span>
                </div>
                <span class="badge">SQLite / MySQL</span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
                Active tables with automatic multi-tenant scoping:
            </p>

            <div style="display: flex; flex-direction: column; gap: 0.5rem; background: rgba(0,0,0,0.35); padding: 0.75rem; border-radius: var(--radius-md);">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.75rem; background: rgba(255,255,255,0.03); border-radius: 6px;">
                    <span style="font-weight: 600; font-family: 'JetBrains Mono'; font-size: 0.85rem;">projects</span>
                    <span class="badge" style="font-size: 0.7rem;">tenant_id: 'default'</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.75rem; background: rgba(255,255,255,0.03); border-radius: 6px;">
                    <span style="font-weight: 600; font-family: 'JetBrains Mono'; font-size: 0.85rem;">users</span>
                    <span class="badge" style="font-size: 0.7rem;">6 records</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.75rem; background: rgba(255,255,255,0.03); border-radius: 6px;">
                    <span style="font-weight: 600; font-family: 'JetBrains Mono'; font-size: 0.85rem;">migrations</span>
                    <span class="badge" style="font-size: 0.7rem;">Synced</span>
                </div>
            </div>
        </div>

        <!-- Panel 3: Realtime Channels & AI Agent Tools -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon">
                        <svg class="icon" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                    </div>
                    <span>AI Agent & Realtime Channels</span>
                </div>
                <span class="badge">Tool Calling</span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">
                Active channel subscriptions and registered AI tool functions:
            </p>

            <div style="display: flex; flex-direction: column; gap: 0.5rem; background: rgba(0,0,0,0.35); padding: 0.75rem; border-radius: var(--radius-md);">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255,255,255,0.03); border-radius: 6px;">
                    <span style="font-size: 0.85rem; color: #fb7185; font-weight: 600;">#[AiTool] checkInventory</span>
                    <span class="badge" style="font-size: 0.7rem;">PHP Method</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255,255,255,0.03); border-radius: 6px;">
                    <span style="font-size: 0.85rem; color: #c084fc; font-weight: 600;">Channel: chat.{room}</span>
                    <span class="badge" style="font-size: 0.7rem;">Authorized</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0.75rem; background: rgba(255,255,255,0.03); border-radius: 6px;">
                    <span style="font-size: 0.85rem; color: #c084fc; font-weight: 600;">Channel: orders.live</span>
                    <span class="badge" style="font-size: 0.7rem;">SSE / WebSocket</span>
                </div>
            </div>
        </div>
    </div>
</div>
