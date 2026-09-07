<?php $this->extends('layout'); ?>

<div class="container">
    <div class="hero">
        <div class="hero-badge">
            <svg class="icon" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <span>Pulse Architecture v1.0.0</span>
        </div>
        <h1>Unified <span>Full-Stack</span> Subsystems</h1>
        <p>You navigated here seamlessly via Pulse SPA pushState mode without a full page reload!</p>
    </div>

    <div class="card" style="margin-top: 2rem; max-width: 960px; margin-left: auto; margin-right: auto;">
        <div class="card-header">
            <div class="card-title">
                <div class="card-title-icon">
                    <svg class="icon" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                </div>
                <span>The 4 Unified Pillars of Pulse</span>
            </div>
            <span class="badge">
                <svg class="icon" style="width: 12px; height: 12px;" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Production Ready</span>
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
            <div style="background: rgba(0,0,0,0.25); padding: 1.25rem; border-radius: 12px; border-left: 3px solid var(--accent-cyan);">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <svg class="icon" style="color: var(--accent-cyan);" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    <h3 style="font-size: 1.05rem; color: var(--accent-cyan); font-weight: 700;">1. Core Identity & DX</h3>
                </div>
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    Tri-Mode Smart Router (SSR ↔ SPA ↔ API), reactive PHP component lifecycle, and lightweight zero-build client runtime (<10KB).
                </p>
            </div>

            <div style="background: rgba(0,0,0,0.25); padding: 1.25rem; border-radius: 12px; border-left: 3px solid var(--accent-indigo);">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <svg class="icon" style="color: var(--accent-indigo);" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    <h3 style="font-size: 1.05rem; color: var(--accent-indigo); font-weight: 700;">2. Application Platform</h3>
                </div>
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    PSR-11 auto-wiring DI container, multi-tenant database ORM, declarative form validation, security middleware pipeline, and event bus.
                </p>
            </div>

            <div style="background: rgba(0,0,0,0.25); padding: 1.25rem; border-radius: 12px; border-left: 3px solid var(--accent-cyan);">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <svg class="icon" style="color: var(--accent-cyan);" viewBox="0 0 24 24"><path d="M4.9 19.1C1 15.2 1 8.8 4.9 4.9"></path><path d="M7.8 16.2c-2.3-2.3-2.3-6.1 0-8.5"></path><circle cx="12" cy="12" r="2"></circle><path d="M16.2 7.8c2.3 2.3 2.3 6.1 0 8.5"></path><path d="M19.1 4.9C23 8.8 23 15.2 19.1 19.1"></path></svg>
                    <h3 style="font-size: 1.05rem; color: var(--accent-cyan); font-weight: 700;">3. Realtime & Async</h3>
                </div>
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    PHP 8.1+ Fiber concurrency (await, all), WebSocket/SSE realtime channels with authorization, and progressive streaming responses.
                </p>
            </div>

            <div style="background: rgba(0,0,0,0.25); padding: 1.25rem; border-radius: 12px; border-left: 3px solid var(--accent-indigo);">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <svg class="icon" style="color: var(--accent-indigo);" viewBox="0 0 24 24"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path></svg>
                    <h3 style="font-size: 1.05rem; color: var(--accent-indigo); font-weight: 700;">4. Advanced Runtime & AI</h3>
                </div>
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    Persistent worker runtime, real-time microsecond performance profiler bar, production request replay, and AI-native token streaming.
                </p>
            </div>
        </div>

        <div style="margin-top: 2rem; text-align: center;">
            <a href="/" class="btn btn-primary">
                <svg class="icon" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                <span>Return to Interactive Components</span>
            </a>
        </div>
    </div>
</div>
