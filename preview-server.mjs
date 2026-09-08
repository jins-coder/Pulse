import http from 'http';
import fs from 'fs';
import path from 'path';
import crypto from 'crypto';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const PORT = 8000;
const SECRET_KEY = 'pulse_framework_secure_hmac_secret_key';

// Mock component states in memory for preview server
let counterState = { count: 5, step: 1 };
let analyticsState = { period: 'monthly', data: [120, 240, 190, 380, 420, 510, 680] };
let searchState = {
    query: '',
    selectedCategory: 'All',
    results: [
        { id: 1, name: 'Sarah Connor', role: 'Principal Engineer', category: 'Engineering', avatar: '👩‍💻' },
        { id: 2, name: 'Alex Rivera', role: 'Lead Product Designer', category: 'Design', avatar: '🎨' },
        { id: 3, name: 'Michael Chen', role: 'Distributed Systems Architect', category: 'Engineering', avatar: '⚡' },
        { id: 4, name: 'Elena Rostova', role: 'DevOps & Cloud Lead', category: 'Operations', avatar: '🚀' },
        { id: 5, name: 'Marcus Vance', role: 'VP of Engineering', category: 'Management', avatar: '👔' },
        { id: 6, name: 'Aria Montgomery', role: 'Frontend Specialist', category: 'Engineering', avatar: '💻' },
    ]
};

let upgradeState = {
    currentTier: 'starter',
    selectedTier: 'horizon',
    billingPeriod: 'annual',
    seats: 5,
    successMessage: null,
    usage: {
        agent_runs: 42,
        queue_jobs: 4120,
        fiber_reqs: 840,
    }
};

let swarmState = {
    goal: 'Analyze high-traffic spikes, optimize fiber pool, and generate security audit',
    status: 'Ready for Swarm Task',
    isExecuting: false,
    latestSynthesis: null,
    swarmLog: []
};

let wasmState = {
    code: "<?php\n\n$greeting = 'Hello from In-Browser WASM PHP 8.4!';\n$primes = [2, 3, 5, 7, 11, 13, 17, 19, 23, 29];\nreturn [\n    'greeting' => $greeting,\n    'primes' => $primes,\n    'memory_kb' => 142.6,\n    'mode' => 'Client WASM Sandbox (0ms Server Latency)',\n];",
    executionResult: {},
    isOffline: false,
    executionCount: 0,
    lastDurationMs: 0.08,
};

let aotState = {
    isOptimized: false,
    selectedModule: 'routing',
    compilationReport: {},
    microvmMetrics: { total_snapshots: 1, active_instances: 0, avg_resurrection_latency_ms: 0.38 },
};

function signSnapshot(className, id, state) {
    const payload = Buffer.from(JSON.stringify({ class: className, id, state, t: Date.now() })).toString('base64');
    const checksum = crypto.createHmac('sha256', SECRET_KEY).update(payload).digest('hex');
    return { payload, checksum };
}

function renderCounter(state) {
    const { payload, checksum } = signSnapshot('App\\Components\\Counter', 'cmp_counter', state);
    return `
    <div data-component="App\\Components\\Counter" data-id="cmp_counter" data-snapshot="${payload}" data-checksum="${checksum}" class="counter-component">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 0.5rem; font-weight: 700;">
                Reactive Public State
            </div>
            <div style="font-size: 3.5rem; font-weight: 800; font-family: 'JetBrains Mono', monospace; color: var(--accent-cyan); line-height: 1; text-shadow: 0 0 25px rgba(56, 189, 248, 0.4);">
                ${state.count}
            </div>
        </div>
        <div style="display: flex; gap: 0.75rem; justify-content: center; margin-bottom: 1.25rem;">
            <button class="btn btn-secondary" action="decrement" title="Decrement" style="width: 46px; height: 46px; padding: 0;">
                <svg class="icon" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            </button>
            <button class="btn btn-primary" action="increment" title="Increment" style="width: 46px; height: 46px; padding: 0;">
                <svg class="icon" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            </button>
            <button class="btn btn-secondary" action="resetCount" title="Reset Counter" style="padding: 0 1.1rem;">
                <svg class="icon" style="width: 16px; height: 16px;" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                <span>Reset</span>
            </button>
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between; background: rgba(0, 0, 0, 0.25); padding: 0.75rem 1rem; border-radius: var(--radius-md); font-size: 0.85rem; border: 1px solid rgba(255, 255, 255, 0.04);">
            <span style="color: var(--text-muted); display: flex; align-items: center; gap: 0.4rem;">
                <svg class="icon" style="width: 15px; height: 15px; color: var(--accent-cyan);" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span>Step Delta:</span>
            </span>
            <input type="number" bind="step" value="${state.step}" min="1" max="100" style="width: 70px; padding: 0.35rem 0.5rem; background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border); border-radius: 6px; color: var(--text-main); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; text-align: center;">
        </div>
    </div>`;
}

function renderUserSearch(state) {
    const { payload, checksum } = signSnapshot('App\\Components\\UserSearch', 'cmp_search', state);
    const filtered = state.results.filter(u => {
        const matchesQ = !state.query || u.name.toLowerCase().includes(state.query.toLowerCase()) || u.role.toLowerCase().includes(state.query.toLowerCase());
        const matchesC = state.selectedCategory === 'All' || u.category === state.selectedCategory;
        return matchesQ && matchesC;
    });

    const categories = ['All', 'Engineering', 'Design', 'Operations', 'Management'];
    const catHtml = categories.map(c => `
        <button action="setCategory('${c}')" class="btn ${state.selectedCategory === c ? 'btn-primary' : 'btn-secondary'}" style="font-size: 0.8rem; padding: 0.35rem 0.75rem; border-radius: 9999px;">
            ${c}
        </button>
    `).join('');

    const resultsHtml = filtered.length === 0
        ? `<div style="text-align: center; padding: 2rem 1rem; color: var(--text-muted); font-size: 0.9rem;">No team members matched "${state.query}" in ${state.selectedCategory}</div>`
        : filtered.map(u => `
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0.75rem; background: rgba(255, 255, 255, 0.03); border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.04);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.25rem;">${u.avatar}</span>
                    <div>
                        <div style="font-weight: 700; font-size: 0.9rem; color: var(--text-main);">${u.name}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">${u.role}</div>
                    </div>
                </div>
                <span class="badge" style="font-size: 0.7rem;">${u.category}</span>
            </div>
        `).join('');

    return `
    <div data-component="App\\Components\\UserSearch" data-id="cmp_search" data-snapshot="${payload}" data-checksum="${checksum}" class="user-search-component">
        <div style="position: relative; margin-bottom: 1rem;">
            <div style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); display: flex; align-items: center; pointer-events: none;">
                <svg class="icon" style="width: 16px; height: 16px;" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <input type="text" bind="query" value="${state.query}" debounce="150" placeholder="Type to filter team members..." class="input-text">
            ${state.query ? `
            <button action="clearSearch" title="Clear" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.08); border: none; border-radius: 4px; padding: 4px; color: var(--text-muted); cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <svg class="icon" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>` : ''}
        </div>
        <div style="display: flex; gap: 0.5rem; margin-bottom: 1.25rem; overflow-x: auto; padding-bottom: 4px;">
            ${catHtml}
        </div>
        <div style="background: rgba(0, 0, 0, 0.25); border-radius: var(--radius-md); padding: 0.5rem; min-height: 180px; max-height: 240px; overflow-y: auto; border: 1px solid rgba(255, 255, 255, 0.04);">
            <div style="font-size: 0.75rem; color: var(--text-muted); padding: 0.4rem 0.6rem; text-transform: uppercase; letter-spacing: 0.06em; display: flex; justify-content: space-between; font-weight: 700;">
                <span style="display: flex; align-items: center; gap: 0.35rem;">
                    <svg class="icon" style="width: 13px; height: 13px;" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Found ${filtered.length} Members</span>
                </span>
                <span>Category: ${state.selectedCategory}</span>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                ${resultsHtml}
            </div>
        </div>
    </div>`;
}

function renderAnalyticsWidget(state) {
    const { payload, checksum } = signSnapshot('App\\Components\\AnalyticsWidget', 'cmp_analytics', state);
    const total = state.data.reduce((a, b) => a + b, 0);
    const avg = (total / state.data.length).toFixed(1);

    return `
    <div data-component="App\\Components\\AnalyticsWidget" data-id="cmp_analytics" data-snapshot="${payload}" data-checksum="${checksum}" class="analytics-widget">
        <div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; font-weight: 700;">
                    Live Revenue Metrics (${state.period})
                </div>
                <div style="font-size: 2rem; font-weight: 800; color: var(--accent-cyan); font-family: 'JetBrains Mono', monospace;">
                    $${total}k <span style="font-size: 0.85rem; color: #4ade80; font-weight: 600;">+18.4%</span>
                </div>
            </div>

            <div style="display: flex; gap: 0.4rem; background: rgba(0,0,0,0.3); padding: 4px; border-radius: 8px;">
                <button action="setPeriod('daily')" class="btn ${state.period === 'daily' ? 'btn-primary' : 'btn-secondary'}" style="font-size: 0.75rem; padding: 0.3rem 0.6rem;">Daily</button>
                <button action="setPeriod('weekly')" class="btn ${state.period === 'weekly' ? 'btn-primary' : 'btn-secondary'}" style="font-size: 0.75rem; padding: 0.3rem 0.6rem;">Weekly</button>
                <button action="setPeriod('monthly')" class="btn ${state.period === 'monthly' ? 'btn-primary' : 'btn-secondary'}" style="font-size: 0.75rem; padding: 0.3rem 0.6rem;">Monthly</button>
            </div>
        </div>

        <div style="position: relative; width: 100%; height: 160px; background: rgba(0,0,0,0.25); border-radius: 12px; padding: 10px; border: 1px solid rgba(255,255,255,0.05);">
            <canvas id="pulse-chart" width="480" height="140" style="width: 100%; height: 100%; display: block;"></canvas>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.85rem; color: var(--text-muted);">
            <span>Average: <strong style="color: var(--text-main); font-family: 'JetBrains Mono';">$${avg}k</strong></span>
            <span style="color: var(--accent-cyan); display: flex; align-items: center; gap: 5px; font-weight: 600;">
                <svg class="icon" style="width: 13px; height: 13px;" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                <span>Client JS Canvas + PHP Server State</span>
            </span>
        </div>

        <script type="pulse/client">
            return {
                mounted(el, wire) {
                    const canvas = el.querySelector('#pulse-chart');
                    if (!canvas) return;
                    const data = ${JSON.stringify(state.data)};
                    this.drawChart(canvas, data);
                    wire.on('updated', (s) => {
                        this.drawChart(canvas, s.data || data);
                    });
                },
                drawChart(canvas, data) {
                    const ctx = canvas.getContext('2d');
                    const width = canvas.width;
                    const height = canvas.height;
                    ctx.clearRect(0, 0, width, height);
                    const max = Math.max(...data, 1);
                    const stepX = width / (data.length - 1);

                    const grad = ctx.createLinearGradient(0, 0, 0, height);
                    grad.addColorStop(0, 'rgba(56, 189, 248, 0.35)');
                    grad.addColorStop(1, 'rgba(56, 189, 248, 0.0)');

                    ctx.beginPath();
                    data.forEach((val, i) => {
                        const x = i * stepX;
                        const y = height - (val / max) * (height - 30) - 15;
                        if (i === 0) ctx.moveTo(x, y);
                        else ctx.lineTo(x, y);
                    });
                    ctx.lineTo(width, height);
                    ctx.lineTo(0, height);
                    ctx.fillStyle = grad;
                    ctx.fill();

                    ctx.beginPath();
                    data.forEach((val, i) => {
                        const x = i * stepX;
                        const y = height - (val / max) * (height - 30) - 15;
                        if (i === 0) ctx.moveTo(x, y);
                        else ctx.lineTo(x, y);
                    });
                    ctx.strokeStyle = '#38bdf8';
                    ctx.lineWidth = 3;
                    ctx.shadowColor = 'rgba(56, 189, 248, 0.8)';
                    ctx.shadowBlur = 10;
                    ctx.stroke();

                    data.forEach((val, i) => {
                        const x = i * stepX;
                        const y = height - (val / max) * (height - 30) - 15;
                        ctx.beginPath();
                        ctx.arc(x, y, 4, 0, Math.PI * 2);
                        ctx.fillStyle = '#f8fafc';
                        ctx.shadowBlur = 4;
                        ctx.fill();
                    });
                }
            };
        </script>
    </div>`;
}

function renderSubscriptionUpgrade(state) {
    const { payload, checksum } = signSnapshot('App\\Components\\SubscriptionUpgrade', 'cmp_upgrade', state);
    const tiers = {
        starter: {
            name: 'Starter (Community)',
            tagline: 'Essential foundation for single-server apps & side projects',
            price: 0,
            badge: 'Free Forever',
            color: '#94a3b8',
            limits: { agent_runs: '50 / mo', queue_jobs: '5,000 / mo', fiber_concurrency: '1,000 req/s' },
            features: {
                'Fiber Reactor Core (50k req/s)': true,
                'PulseX Single-File Components': true,
                'Zero-Build SPA Navigation': true,
                'Autonomous Agent Mesh': false,
                'In-Browser WASM PHP': false,
                'AOT Bytecode Optimization': false,
            }
        },
        pro: {
            name: 'Pro Growth',
            tagline: 'High-throughput scaling for growing SaaS startups',
            price: state.billingPeriod === 'annual' ? 24 : 29,
            badge: 'Most Popular',
            color: '#38bdf8',
            limits: { agent_runs: '5,000 / mo', queue_jobs: '100,000 / mo', fiber_concurrency: '15,000 req/s' },
            features: {
                'Fiber Reactor Core (50k req/s)': true,
                'PulseX Single-File Components': true,
                'Zero-Build SPA Navigation': true,
                'Autonomous Agent Mesh': true,
                'In-Browser WASM PHP': true,
                'AOT Bytecode Optimization': false,
            }
        },
        enterprise: {
            name: 'Enterprise Scale',
            tagline: 'Distributed edge clustering with zero-downtime persistence',
            price: state.billingPeriod === 'annual' ? 79 : 99,
            badge: 'High Scale',
            color: '#818cf8',
            limits: { agent_runs: '50,000 / mo', queue_jobs: '2,000,000 / mo', fiber_concurrency: '50,000+ req/s' },
            features: {
                'Fiber Reactor Core (50k req/s)': true,
                'PulseX Single-File Components': true,
                'Zero-Build SPA Navigation': true,
                'Autonomous Agent Mesh': true,
                'In-Browser WASM PHP': true,
                'AOT Bytecode Optimization': true,
            }
        },
        horizon: {
            name: 'Infinity Micro-VM Tier',
            tagline: 'Autonomous self-driving infrastructure & sub-millisecond Micro-VMs',
            price: state.billingPeriod === 'annual' ? 199 : 249,
            badge: '⚡ v4.0 Ultimate',
            color: '#f43f5e',
            limits: { agent_runs: 'Unlimited Swarms', queue_jobs: 'Unlimited Persistent', fiber_concurrency: 'Micro-VMs' },
            features: {
                'Fiber Reactor Core (50k req/s)': true,
                'PulseX Single-File Components': true,
                'Zero-Build SPA Navigation': true,
                'Autonomous Agent Mesh': true,
                'In-Browser WASM PHP': true,
                'AOT Bytecode Optimization': true,
            }
        }
    };

    const cardsHtml = Object.entries(tiers).map(([id, t]) => {
        const isSelected = state.selectedTier === id;
        const isCurrent = state.currentTier === id;
        const feats = Object.entries(t.features).map(([feat, ok]) => `
            <li style="display: flex; align-items: center; gap: 0.5rem; color: ${ok ? '#f8fafc' : 'rgba(255,255,255,0.3)'};">
                <span style="color: ${ok ? '#4ade80' : 'rgba(255,255,255,0.2)'};">${ok ? '✓' : '✕'}</span>
                <span>${feat}</span>
            </li>
        `).join('');

        return `
        <div class="glass-card" style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; border: 2px solid ${isSelected ? '#38bdf8' : 'rgba(255,255,255,0.05)'}; background: ${isSelected ? 'rgba(56, 189, 248, 0.08)' : 'rgba(15, 23, 42, 0.6)'}; border-radius: 16px;">
            <div>
                ${t.badge ? `<span class="badge" style="background: ${t.color}; color: #0f172a; font-weight: 800; font-size: 0.7rem;">${t.badge}</span>` : ''}
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #fff; margin: 0.5rem 0 0.25rem 0;">${t.name}</h3>
                <p style="font-size: 0.8rem; color: var(--text-muted); min-height: 38px; margin: 0 0 1rem 0;">${t.tagline}</p>
                <div style="font-size: 2.2rem; font-weight: 800; font-family: 'JetBrains Mono'; color: ${t.color}; margin-bottom: 1rem;">
                    $${t.price} <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 400;">/ mo</span>
                </div>
                <ul style="list-style: none; padding: 0; margin: 0 0 1.25rem 0; font-size: 0.8rem; display: flex; flex-direction: column; gap: 0.35rem;">
                    ${feats}
                </ul>
            </div>
            <div>
                ${isCurrent ? `<button class="btn btn-secondary" disabled style="width: 100%; opacity: 0.7;">✓ Current Plan</button>` : `<button action="selectTier('${id}')" class="btn ${isSelected ? 'btn-primary' : 'btn-secondary'}" style="width: 100%; font-weight: 700;">${isSelected ? 'Selected' : 'Select ' + t.name}</button>`}
            </div>
        </div>`;
    }).join('');

    return `
    <div data-component="App\\Components\\SubscriptionUpgrade" data-id="cmp_upgrade" data-snapshot="${payload}" data-checksum="${checksum}" class="subscription-upgrade-component">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--accent-cyan); font-weight: 700; letter-spacing: 0.08em;">Tenant Subscription Cockpit</div>
                <h2 style="font-size: 1.75rem; font-weight: 800; color: #fff; margin: 0;">Upgrade Account Plan & Subsystems</h2>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; background: rgba(15, 23, 42, 0.9); border: 1px solid var(--border); padding: 4px; border-radius: 9999px;">
                <button action="setPeriod('monthly')" class="btn ${state.billingPeriod === 'monthly' ? 'btn-primary' : 'btn-secondary'}" style="border-radius: 9999px; padding: 0.4rem 1.1rem; font-size: 0.85rem;">Monthly</button>
                <button action="setPeriod('annual')" class="btn ${state.billingPeriod === 'annual' ? 'btn-primary' : 'btn-secondary'}" style="border-radius: 9999px; padding: 0.4rem 1.1rem; font-size: 0.85rem;">Annual (SAVE 20%)</button>
            </div>
        </div>

        ${state.successMessage ? `<div style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.4); color: #4ade80; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600;">${state.successMessage}</div>` : ''}

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            ${cardsHtml}
        </div>

        ${state.selectedTier !== state.currentTier ? `
        <div class="glass-card" style="padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; background: rgba(56, 189, 248, 0.08); border: 1px solid #38bdf8; border-radius: 16px;">
            <div>
                <div style="font-size: 1.1rem; font-weight: 700; color: #fff;">Confirm Upgrade to <strong style="color: #38bdf8;">${tiers[state.selectedTier]?.name}</strong> (${state.billingPeriod})</div>
                <div style="font-size: 0.85rem; color: var(--text-muted);">Instant access to WASM PHP, AOT Opcode Cache, AI Mesh, and Micro-VMs.</div>
            </div>
            <button action="upgradePlan" class="btn btn-primary" style="padding: 0.85rem 2.2rem; font-size: 1.05rem; font-weight: 800; border-radius: 10px;">⚡ Instant Upgrade</button>
        </div>` : ''}
    </div>`;
}

function renderAgentMeshVisualizer(state) {
    const { payload, checksum } = signSnapshot('App\\Components\\AgentMeshVisualizer', 'cmp_agent_mesh', state);
    const workers = [
        { id: 'architect', name: 'Architect Agent', role: 'System Design & Structure', systemPrompt: 'Designs distributed systems, data models, and API interfaces.', tools: ['analyzeArchitecture', 'generateInterfaceSchema'] },
        { id: 'coder', name: 'Code Synthesizer', role: 'Code Generation & Optimization', systemPrompt: 'Generates high-performance reactive PHP and PulseX components.', tools: ['compilePulseX', 'verifyTypes'] },
        { id: 'qa_reviewer', name: 'QA & Security Verifier', role: 'Security Audit & Test Verification', systemPrompt: 'Verifies cryptographic HMAC state signatures and code safety.', tools: ['verifyHmacSignatures', 'runDiagnostics'] }
    ];

    const workersHtml = workers.map(w => `
        <div class="glass-card" style="padding: 1rem 1.25rem; border: 1px solid rgba(168, 85, 247, 0.2); background: rgba(168, 85, 247, 0.04); border-radius: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                <div>
                    <div style="font-weight: 700; color: #fff; font-size: 0.95rem;">${w.name}</div>
                    <div style="font-size: 0.75rem; color: #c084fc;">${w.role}</div>
                </div>
                <span class="badge" style="font-size: 0.65rem; background: rgba(0,0,0,0.4); color: var(--text-muted); font-family: 'JetBrains Mono';">${w.id}</span>
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.75rem;">${w.systemPrompt}</div>
            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                ${w.tools.map(t => `<span class="badge" style="font-size: 0.65rem; background: rgba(56, 189, 248, 0.1); color: #38bdf8; font-family: 'JetBrains Mono';">#[AiTool] ${t}</span>`).join('')}
            </div>
        </div>
    `).join('');

    const logsHtml = (state.swarmLog || []).map(l => `
        <div style="display: flex; gap: 0.75rem; padding: 0.5rem; background: rgba(255,255,255,0.02); border-radius: 6px;">
            <span style="color: #38bdf8;">[${l.agent}]</span>
            <span style="color: #e2e8f0;">${l.message || l.result}</span>
        </div>
    `).join('');

    return `
    <div data-component="App\\Components\\AgentMeshVisualizer" data-id="cmp_agent_mesh" data-snapshot="${payload}" data-checksum="${checksum}" class="agent-mesh-visualizer">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; color: #a855f7; font-weight: 700; letter-spacing: 0.08em;">Autonomous Multi-Agent Mesh</div>
                <h3 style="font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0;">Live Swarm Orchestration Engine</h3>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="badge" style="background: rgba(168, 85, 247, 0.15); color: #c084fc;">3 Active Workers</span>
                <span class="badge" style="background: rgba(34, 197, 94, 0.15); color: #4ade80;">${state.status}</span>
            </div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            ${workersHtml}
        </div>
        <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.06); padding: 1.25rem; border-radius: 12px; margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem; font-weight: 600;">Swarm Orchestration Goal</label>
            <div style="display: flex; gap: 0.75rem;">
                <input type="text" bind="goal" value="${state.goal}" style="flex: 1; padding: 0.65rem 1rem; background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border); border-radius: 8px; color: #fff; font-size: 0.9rem;">
                <button action="runSwarm" class="btn btn-primary" style="padding: 0.65rem 1.5rem; font-weight: 700; background: linear-gradient(135deg, #a855f7, #6366f1); border: none;">⚡ Dispatch Swarm</button>
            </div>
        </div>
        ${state.swarmLog && state.swarmLog.length > 0 ? `
        <div style="background: rgba(0,0,0,0.4); border: 1px solid rgba(168, 85, 247, 0.3); padding: 1.25rem; border-radius: 12px;">
            <div style="font-size: 0.8rem; text-transform: uppercase; color: #c084fc; font-weight: 700; margin-bottom: 0.75rem;">Swarm Execution Trace</div>
            <div style="display: flex; flex-direction: column; gap: 0.6rem; font-family: 'JetBrains Mono', monospace; font-size: 0.8rem;">
                ${logsHtml}
            </div>
            ${state.latestSynthesis ? `<div style="margin-top: 1rem; padding: 0.75rem 1rem; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; color: #4ade80; font-size: 0.85rem; font-weight: 600;">✓ Synthesis: ${state.latestSynthesis}</div>` : ''}
        </div>` : ''}
    </div>`;
}

function renderWasmPlayground(state) {
    const { payload, checksum } = signSnapshot('App\\Components\\WasmPlayground', 'cmp_wasm', state);
    return `
    <div data-component="App\\Components\\WasmPlayground" data-id="cmp_wasm" data-snapshot="${payload}" data-checksum="${checksum}" class="wasm-playground-component">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; color: #38bdf8; font-weight: 700; letter-spacing: 0.08em;">In-Browser WebAssembly (WASM) Engine</div>
                <h3 style="font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0;">Zero-Latency Client-Side PHP 8.4 Sandbox</h3>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <button action="toggleOffline" class="btn ${state.isOffline ? 'btn-primary' : 'btn-secondary'}" style="font-size: 0.8rem; padding: 0.35rem 0.85rem; border-radius: 9999px;">
                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: ${state.isOffline ? '#f43f5e' : '#4ade80'}; margin-right: 4px;"></span>
                    <span>Mode: ${state.isOffline ? 'Offline (IndexedDB)' : 'Online (Edge Connected)'}</span>
                </button>
                <span class="badge" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8; font-size: 0.75rem;">${state.executionCount} Runs</span>
            </div>
        </div>
        <div style="background: rgba(11, 7, 20, 0.95); border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem;">
            <div style="background: rgba(255,255,255,0.03); padding: 0.6rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; justify-content: space-between; align-items: center;">
                <span style="font-family: 'JetBrains Mono'; font-size: 0.8rem; color: var(--text-muted);">main.wasm.php</span>
                <span style="font-size: 0.75rem; color: #38bdf8; font-weight: 600;">WASM JIT Ready (16MB Heap)</span>
            </div>
            <textarea bind="code" rows="8" style="width: 100%; padding: 1rem; background: transparent; border: none; color: #f8fafc; font-family: 'JetBrains Mono', monospace; font-size: 0.88rem; line-height: 1.5; resize: vertical; outline: none;">${state.code}</textarea>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <button action="runInWasm" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem; font-weight: 800; background: linear-gradient(135deg, #38bdf8, #818cf8); border: none;">⚡ Run In-Browser WASM (0ms Latency)</button>
            ${state.executionCount > 0 ? `<div style="font-family: 'JetBrains Mono'; font-size: 0.85rem; color: #4ade80;">Duration: <strong>${state.lastDurationMs} ms</strong> • Memory: <strong>142.6 KB</strong></div>` : ''}
        </div>
        ${state.executionResult && Object.keys(state.executionResult).length > 0 ? `
        <div style="background: rgba(0, 0, 0, 0.6); border: 1px solid rgba(74, 222, 128, 0.3); border-radius: 12px; padding: 1.25rem;">
            <pre style="margin: 0; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: #fdf4ff;">${JSON.stringify(state.executionResult, null, 2)}</pre>
        </div>` : ''}
    </div>`;
}

function renderAotVisualizer(state) {
    const { payload, checksum } = signSnapshot('App\\Components\\AotVisualizer', 'cmp_aot', state);
    return `
    <div data-component="App\\Components\\AotVisualizer" data-id="cmp_aot" data-snapshot="${payload}" data-checksum="${checksum}" class="aot-visualizer-component">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; color: #a855f7; font-weight: 700; letter-spacing: 0.08em;">Ahead-of-Time (AOT) & Micro-VM Engine</div>
                <h3 style="font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0;">Sub-Millisecond Cold Starts & Opcode Caching</h3>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button action="runAotOptimization" class="btn btn-primary" style="background: linear-gradient(135deg, #a855f7, #6366f1); font-size: 0.85rem; padding: 0.4rem 1rem;">⚡ Pre-Compile Full App AOT</button>
                <button action="spawnServerlessMicroVM" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.4rem 1rem;">🚀 Spawn 0.38ms Micro-VM</button>
            </div>
        </div>
        <div class="glass-card" style="padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem;">
            <div>
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Cold Boot Latency</div>
                <div style="font-size: 1.6rem; font-weight: 800; font-family: 'JetBrains Mono'; color: #4ade80;">0.12 ms</div>
                <div style="font-size: 0.75rem; color: #4ade80;">98.4% faster vs FPM</div>
            </div>
            <div>
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Micro-VM Snapshots</div>
                <div style="font-size: 1.6rem; font-weight: 800; font-family: 'JetBrains Mono'; color: #38bdf8;">${state.microvmMetrics?.total_snapshots || 1} Ready</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Instant memory state</div>
            </div>
            <div>
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Active Instances</div>
                <div style="font-size: 1.6rem; font-weight: 800; font-family: 'JetBrains Mono'; color: #c084fc;">${state.microvmMetrics?.active_instances || 0} Live</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Scale-to-zero in 0.05ms</div>
            </div>
        </div>
        ${state.isOptimized ? `
        <div style="background: rgba(168, 85, 247, 0.08); border: 1px solid rgba(168, 85, 247, 0.4); border-radius: 12px; padding: 1.25rem;">
            <div style="font-size: 0.85rem; font-weight: 700; color: #c084fc; margin-bottom: 0.75rem; text-transform: uppercase;">✓ Ahead-of-Time Opcode Compilation Manifest</div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; font-family: 'JetBrains Mono'; font-size: 0.82rem;">
                <div style="background: rgba(0,0,0,0.3); padding: 0.6rem; border-radius: 8px;"><span style="color: var(--text-muted);">Static Routes:</span> <strong style="color: #fff;">${state.compilationReport?.compiled_routes || 12}</strong></div>
                <div style="background: rgba(0,0,0,0.3); padding: 0.6rem; border-radius: 8px;"><span style="color: var(--text-muted);">DI Services:</span> <strong style="color: #fff;">${state.compilationReport?.container_services || 24}</strong></div>
                <div style="background: rgba(0,0,0,0.3); padding: 0.6rem; border-radius: 8px;"><span style="color: var(--text-muted);">Bytecode:</span> <strong style="color: #38bdf8;">${state.compilationReport?.bytecode_size_kb || 38.4} KB</strong></div>
                <div style="background: rgba(0,0,0,0.3); padding: 0.6rem; border-radius: 8px;"><span style="color: var(--text-muted);">Cold Start:</span> <strong style="color: #4ade80;">0.12 ms</strong></div>
            </div>
        </div>` : ''}
    </div>`;
}

function getLayout(content, title = 'Pulse PHP Application Framework') {
    return `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>${title}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0b0714;
            --bg-secondary: #150d24;
            --bg-card: rgba(26, 16, 44, 0.72);
            --border: rgba(255, 255, 255, 0.08);
            --border-hover: rgba(244, 63, 94, 0.45);
            --text-main: #fdf4ff;
            --text-muted: #c4b5fd;
            --accent-rose: #f43f5e;
            --accent-violet: #a855f7;
            --accent-purple: #c084fc;
            --accent-cyan: #38bdf8;
            --accent-indigo: #818cf8;
            --gradient-accent: linear-gradient(135deg, #f43f5e 0%, #a855f7 50%, #6366f1 100%);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-main);
            min-height: 100vh;
            background-image: 
                radial-gradient(at 0% 0%, rgba(244, 63, 94, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.12) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(99, 102, 241, 0.05) 0px, transparent 60%);
            background-attachment: fixed;
            line-height: 1.6;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.1rem 2.5rem;
            background: rgba(21, 13, 36, 0.82);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-main);
            font-weight: 800;
            font-size: 1.3rem;
            letter-spacing: -0.02em;
        }
        .brand-icon {
            width: 34px;
            height: 34px;
            background: var(--gradient-accent);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 0 18px rgba(244, 63, 94, 0.6);
        }
        .brand-badge {
            background: rgba(244, 63, 94, 0.15);
            border: 1px solid rgba(244, 63, 94, 0.4);
            color: #fb7185;
            padding: 0.15rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
        }
        .nav-links { display: flex; gap: 1.25rem; align-items: center; }
        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.92rem;
            transition: color 0.2s ease;
        }
        .nav-link:hover, .nav-link.active { color: #f43f5e; }
        .container { max-width: 1200px; margin: 0 auto; padding: 2.5rem 1.5rem 5rem; }
        .hero { text-align: center; padding: 3rem 1rem 2rem; max-width: 850px; margin: 0 auto; }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            background: rgba(244, 63, 94, 0.12);
            border: 1px solid rgba(244, 63, 94, 0.35);
            border-radius: 9999px;
            color: #fb7185;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin-bottom: 1.25rem;
        }
        .hero h1 span {
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            font-size: 1.15rem;
            color: var(--text-muted);
            line-height: 1.7;
            max-width: 700px;
            margin: 0 auto;
        }
        .grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.75rem; margin-top: 3rem; }
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.75rem;
            backdrop-filter: blur(12px);
            transition: border-color 0.2s ease, transform 0.2s ease;
        }
        .card:hover { border-color: var(--border-hover); transform: translateY(-2px); }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
        .card-title { font-weight: 700; font-size: 1.1rem; color: var(--text-main); display: flex; align-items: center; gap: 0.6rem; }
        .card-title-icon { width: 30px; height: 30px; border-radius: 7px; background: rgba(244, 63, 94, 0.16); color: #fb7185; display: flex; align-items: center; justify-content: center; }
        .badge { font-size: 0.75rem; padding: 0.25rem 0.6rem; border-radius: 9999px; font-weight: 700; background: rgba(255, 255, 255, 0.08); color: #fb7185; display: inline-flex; align-items: center; gap: 0.3rem; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.65rem 1.25rem;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            font-family: inherit;
        }
        .btn-primary { background: var(--gradient-accent); color: #ffffff; box-shadow: 0 4px 15px rgba(244, 63, 94, 0.4); }
        .btn-primary:hover { transform: scale(1.02); box-shadow: 0 6px 22px rgba(244, 63, 94, 0.6); }
        .btn-secondary { background: rgba(255, 255, 255, 0.08); color: var(--text-main); border: 1px solid var(--border); }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.25); }
        .input-text {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            background: rgba(21, 13, 36, 0.85);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text-main);
            font-size: 0.95rem;
            font-family: inherit;
            outline: none;
        }
        .icon { width: 18px; height: 18px; stroke-width: 2; stroke: currentColor; fill: none; stroke-linecap: round; stroke-linejoin: round; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="brand">
            <div class="brand-icon">
                <svg class="icon" style="stroke: #ffffff; stroke-width: 2.5;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            </div>
            <span>PULSE</span>
            <span class="brand-badge">v4.0.0</span>
        </a>
        <div class="nav-links">
            <a href="/" class="nav-link">
                <svg class="icon" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 22"></polyline></svg>
                <span>Home</span>
            </a>
            <a href="/wasm" class="nav-link" style="color: #38bdf8;">
                <svg class="icon" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                <span>WASM PHP</span>
            </a>
            <a href="/aot" class="nav-link" style="color: #a855f7;">
                <svg class="icon" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                <span>AOT & Micro-VM</span>
            </a>
            <a href="/agents" class="nav-link" style="color: #c084fc;">
                <svg class="icon" viewBox="0 0 24 24"><path d="M12 2a8 8 0 0 0-8 8c0 3.36 2.07 6.24 5 7.42V20a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2.58c2.93-1.18 5-4.06 5-7.42a8 8 0 0 0-8-8z"></path></svg>
                <span>AI Agents</span>
            </a>
            <a href="/upgrade" class="nav-link" style="color: #38bdf8; font-weight: 700;">
                <svg class="icon" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span>Upgrade</span>
            </a>
            <a href="/_pulse/studio" class="nav-link" style="color: #fb7185;">
                <svg class="icon" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                <span>Studio</span>
            </a>
            <a href="/api/info" target="_blank" class="nav-link">
                <svg class="icon" viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>
                <span>API JSON</span>
            </a>
        </div>
    </nav>

    <main id="app">
        ${content}
    </main>

    <!-- Pulse Performance Profiler Bar -->
    <div id="pulse-profiler" style="position:fixed;bottom:0;left:0;right:0;background:rgba(21,13,36,0.92);backdrop-filter:blur(10px);border-top:1px solid rgba(244,63,94,0.3);color:#fdf4ff;font-family:'JetBrains Mono',monospace;font-size:11px;padding:6px 16px;display:flex;gap:22px;align-items:center;z-index:99999;box-shadow:0 -4px 20px rgba(0,0,0,0.6);">
        <div style="font-weight:bold;color:#fb7185;display:flex;align-items:center;gap:6px;">
            <svg style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            <span>PULSE v4.0.0 (Infinity)</span>
        </div>
        <div style="display:flex;align-items:center;gap:5px;">
            <svg style="width:13px;height:13px;stroke:#4ade80;fill:none;stroke-width:2;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <span>Time: <strong style="color:#4ade80;">0.12 ms (AOT)</strong></span>
        </div>
        <div style="display:flex;align-items:center;gap:5px;">
            <svg style="width:13px;height:13px;stroke:#38bdf8;fill:none;stroke-width:2;" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon></svg>
            <span>WASM: <strong style="color:#38bdf8;">0.08ms JIT</strong></span>
        </div>
        <div style="display:flex;align-items:center;gap:5px;">
            <svg style="width:13px;height:13px;stroke:#c084fc;fill:none;stroke-width:2;" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect></svg>
            <span>Micro-VM: <strong style="color:#c084fc;">0.38ms boot</strong></span>
        </div>
        <div style="display:flex;align-items:center;gap:5px;">
            <svg style="width:13px;height:13px;stroke:#fbbf24;fill:none;stroke-width:2;" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse></svg>
            <span>Reactor: <strong style="color:#fbbf24;">58,500 req/s</strong></span>
        </div>
    </div>

    <script src="/pulse.js"></script>
</body>
</html>`;
}

function getHomeContent() {
    return `
    <div class="container">
        <div class="hero">
            <div class="hero-badge">
                <svg class="icon" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span>v4.0.0 Infinity • In-Browser WASM PHP • AOT Opcode Compilation • Sub-1ms Micro-VMs</span>
            </div>
            <h1>PHP runs anywhere.<br><span>From Cloud Fibers to Browser WASM.</span></h1>
            <p>A breakthrough full-stack runtime running the exact same PHP components on in-memory server Fiber reactors, client-side browser WebAssembly engines, and instant serverless Micro-VM containers.</p>
            <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 1.75rem; flex-wrap: wrap;">
                <a href="/wasm" class="btn btn-primary" style="background: linear-gradient(135deg, #38bdf8, #818cf8); box-shadow: 0 4px 18px rgba(56, 189, 248, 0.5); font-size: 1rem; padding: 0.75rem 1.75rem;">
                    ⚡ Try In-Browser WASM PHP
                </a>
                <a href="/aot" class="btn btn-secondary" style="font-size: 1rem; padding: 0.75rem 1.75rem; border-color: rgba(168, 85, 247, 0.4); color: #c084fc;">
                    🚀 AOT & Micro-VM Benchmarks
                </a>
                <a href="/agents" class="btn btn-secondary" style="font-size: 1rem; padding: 0.75rem 1.75rem;">
                    🤖 AI Multi-Agent Swarm
                </a>
                <a href="/_pulse/studio" class="btn btn-secondary" style="font-size: 1rem; padding: 0.75rem 1.75rem;">
                    🎛️ Studio Cockpit
                </a>
            </div>
        </div>

        <div class="grid-2">
            <div class="card" style="grid-column: 1 / -1;">
                <div class="card-header">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M3 3v18h18"></path><path d="M18 17V9"></path><path d="M13 17V5"></path><path d="M8 17v-3"></path></svg>
                        </div>
                        <span>PulseX Hybrid Component: Live Chart (PHP + Client Canvas JS)</span>
                    </div>
                    <span class="badge">
                        <svg class="icon" style="width: 12px; height: 12px;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                        <span>Co-Located JS & PHP</span>
                    </span>
                </div>
                ${renderAnalyticsWidget(analyticsState)}
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg class="icon" viewBox="0 0 24 24"><rect x="4" y="2" width="16" height="20" rx="2"></rect></svg>
                        </div>
                        <span>Reactive State Counter</span>
                    </div>
                    <span class="badge">HMAC Signed</span>
                </div>
                ${renderCounter(counterState)}
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg class="icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle></svg>
                        </div>
                        <span>Instant Live Search</span>
                    </div>
                    <span class="badge">Debounced Sync</span>
                </div>
                ${renderUserSearch(searchState)}
            </div>
        </div>
    </div>`;
}

function getUpgradeContent() {
    return `
    <div class="container">
        ${renderSubscriptionUpgrade(upgradeState)}
    </div>`;
}

function getAgentsContent() {
    return `
    <div class="container">
        <div class="hero">
            <div class="hero-badge">
                <svg class="icon" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span>Pulse Autonomous AI Swarm Subsystem</span>
            </div>
            <h1>Autonomous <span>Multi-Agent</span> Mesh</h1>
            <p>Collaborative swarms of specialized PHP agents running concurrently on Fibers, sharing a distributed blackboard memory, and coordinating tool calls.</p>
        </div>
        <div class="card" style="margin-top: 2rem;">
            ${renderAgentMeshVisualizer(swarmState)}
        </div>
    </div>`;
}

function getWasmContent() {
    return `
    <div class="container">
        <div class="hero">
            <div class="hero-badge">
                <svg class="icon" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span>Pulse v4.0 (Infinity) WebAssembly Engine</span>
            </div>
            <h1>In-Browser <span>WASM PHP</span> 8.4</h1>
            <p>Execute standard PHP code and reactive Pulse components directly in browser WebAssembly with 0ms server round-trip latency and offline IndexedDB persistence.</p>
        </div>
        <div class="card" style="margin-top: 2rem;">
            ${renderWasmPlayground(wasmState)}
        </div>
    </div>`;
}

function getAotContent() {
    return `
    <div class="container">
        <div class="hero">
            <div class="hero-badge">
                <svg class="icon" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span>Pulse v4.0 AOT Opcode & Micro-VM Engine</span>
            </div>
            <h1>Ahead-of-Time <span>Compilation</span> & Micro-VMs</h1>
            <p>Pre-compile routes and dependency graphs into static binary opcode bundles. Resurrect serverless micro-VM containers in 0.38ms with copy-on-write memory snapshots.</p>
        </div>
        <div class="card" style="margin-top: 2rem;">
            ${renderAotVisualizer(aotState)}
        </div>
    </div>`;
}

const server = http.createServer((req, res) => {
    const url = new URL(req.url, `http://${req.headers.host}`);
    const pathname = url.pathname;
    const isSpa = req.headers['x-requested-mode'] === 'spa';
    const isReactive = req.headers['x-reactive-action'] === 'true' || pathname === '/_pulse/action' || pathname === '/_framework/action';

    if (pathname === '/pulse.js' || pathname === '/runtime.js') {
        const js = fs.readFileSync(path.join(__dirname, 'public/pulse.js'), 'utf8');
        res.writeHead(200, { 'Content-Type': 'application/javascript' });
        res.end(js);
        return;
    }

    if (pathname === '/api/info') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({
            framework: 'Pulse PHP Application Framework',
            version: '4.0.0',
            codename: 'Infinity',
            architecture: {
                in_browser_wasm: 'PHP 8.4 WebAssembly with JIT & Offline IndexedDB',
                aot_compiler: 'Ahead-of-Time Bytecode & Opcode Bundle Cache',
                serverless_microvm: 'Sub-Millisecond Snapshot Resurrection (0.38ms)',
                ai_multi_agent_mesh: 'Autonomous Swarm Orchestration & #[AiTool]',
                self_healing_queues: 'DLQ Diagnosis & Jitter Auto-Remediation',
                distributed_crdt: 'LWW-Register & PN-Counter Edge Replication',
                opentelemetry: 'Zero-Config Distributed Tracing & W3C Spans',
                persistent_reactor: 'Persistent Fiber Reactor Server (58,500 req/s)',
            },
            status: 'operational',
            timestamp: timeNow()
        }, null, 2));
        return;
    }

    if (pathname === '/_pulse/wasm') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({
            engine: 'Pulse WebAssembly Client Runtime',
            version: '4.0.0',
            manifest: { wasm_binary_url: '/pulse-engine.wasm', heap_initial_pages: 256, offline_capable: true },
            timestamp: timeNow()
        }, null, 2));
        return;
    }

    if (pathname === '/_pulse/aot') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({
            compiler: 'Pulse Ahead-of-Time Bytecode Compiler',
            version: '4.0.0',
            stats: { entries_count: 8, hits: 1420, hit_rate: '99.4%', cold_start_ms: 0.12 },
            timestamp: timeNow()
        }, null, 2));
        return;
    }

    if (pathname === '/_pulse/microvm') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({
            kernel: 'Pulse Sub-Millisecond Micro-VM Serverless Engine',
            version: '4.0.0',
            metrics: { total_snapshots: 1, active_instances: aotState.microvmMetrics?.active_instances || 0, avg_boot_ms: 0.38 },
            timestamp: timeNow()
        }, null, 2));
        return;
    }

    if (pathname === '/_pulse/telemetry') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({
            framework: 'Pulse OpenTelemetry Distributed Tracing',
            version: '4.0.0',
            trace_id: 'trc_' + Date.now().toString(16),
            spans: [
                { name: 'http.request /wasm', duration_ms: 0.12, status: 'OK' },
                { name: 'wasm.execute main.wasm.php', duration_ms: 0.08, status: 'OK' },
                { name: 'microvm.resurrect snap_base_001', duration_ms: 0.38, status: 'OK' }
            ],
            timestamp: timeNow()
        }, null, 2));
        return;
    }

    if (pathname === '/_pulse/agents') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({
            mesh: 'Pulse Multi-Agent Swarm Orchestrator',
            version: '4.0.0',
            workers_count: 3,
            blackboard_keys: ['active_goal', 'latest_synthesis'],
            timestamp: timeNow()
        }, null, 2));
        return;
    }

    if (pathname === '/_pulse/queues') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({
            queue: 'Pulse Self-Healing Distributed Queue',
            version: '4.0.0',
            metrics: { queued: 0, dlq_count: 0, healed_count: 142 },
            timestamp: timeNow()
        }, null, 2));
        return;
    }

    if (isReactive && req.method === 'POST') {
        let body = '';
        req.on('data', chunk => body += chunk);
        req.on('end', () => {
            const data = JSON.parse(body || '{}');
            const { id, updates, action, params } = data;

            if (id === 'cmp_counter') {
                if (updates?.step !== undefined) counterState.step = parseInt(updates.step, 10) || 1;
                if (action === 'increment') counterState.count += counterState.step;
                if (action === 'decrement') counterState.count -= counterState.step;
                if (action === 'resetCount') counterState.count = 0;

                const html = renderCounter(counterState);
                res.writeHead(200, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ id, html, state: counterState, success: true }));
                return;
            }

            if (id === 'cmp_analytics') {
                if (action === 'setPeriod' && params?.[0]) {
                    analyticsState.period = params[0];
                    if (params[0] === 'weekly') analyticsState.data = [85, 130, 95, 210, 180, 290, 340];
                    else if (params[0] === 'daily') analyticsState.data = [20, 45, 60, 55, 90, 110, 140];
                    else analyticsState.data = [120, 240, 190, 380, 420, 510, 680];
                }

                const html = renderAnalyticsWidget(analyticsState);
                res.writeHead(200, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ id, html, state: analyticsState, success: true }));
                return;
            }

            if (id === 'cmp_search') {
                if (updates?.query !== undefined) searchState.query = updates.query;
                if (action === 'setCategory' && params?.[0]) searchState.selectedCategory = params[0];
                if (action === 'clearSearch') { searchState.query = ''; searchState.selectedCategory = 'All'; }

                const html = renderUserSearch(searchState);
                res.writeHead(200, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ id, html, state: searchState, success: true }));
                return;
            }

            if (id === 'cmp_upgrade') {
                if (action === 'setPeriod' && params?.[0]) upgradeState.billingPeriod = params[0];
                if (action === 'selectTier' && params?.[0]) upgradeState.selectedTier = params[0];
                if (action === 'upgradePlan') {
                    upgradeState.currentTier = upgradeState.selectedTier;
                    upgradeState.successMessage = `Successfully upgraded to ${upgradeState.currentTier.toUpperCase()} plan!`;
                }

                const html = renderSubscriptionUpgrade(upgradeState);
                res.writeHead(200, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ id, html, state: upgradeState, toasts: [{ message: `Upgraded to ${upgradeState.currentTier} plan!`, type: 'success' }], success: true }));
                return;
            }

            if (id === 'cmp_agent_mesh') {
                if (updates?.goal !== undefined) swarmState.goal = updates.goal;
                if (action === 'runSwarm') {
                    swarmState.status = 'Swarm Completed';
                    swarmState.swarmLog = [
                        { agent: 'Architect Agent', message: 'Decomposed goal into specialized sub-tasks across mesh.' },
                        { agent: 'Code Synthesizer', message: 'Generated high-performance reactive PHP and PulseX components.' },
                        { agent: 'QA & Security Verifier', message: 'Verified HMAC state signatures and code safety.' }
                    ];
                    swarmState.latestSynthesis = `Swarm successfully executed goal: "${swarmState.goal}"`;
                }

                const html = renderAgentMeshVisualizer(swarmState);
                res.writeHead(200, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ id, html, state: swarmState, toasts: [{ message: 'Swarm completed goal!', type: 'success' }], success: true }));
                return;
            }

            if (id === 'cmp_wasm') {
                if (updates?.code !== undefined) wasmState.code = updates.code;
                if (action === 'toggleOffline') wasmState.isOffline = !wasmState.isOffline;
                if (action === 'runInWasm') {
                    wasmState.executionCount++;
                    wasmState.lastDurationMs = 0.08;
                    wasmState.executionResult = {
                        greeting: 'Hello from In-Browser WASM PHP 8.4!',
                        primes: [2, 3, 5, 7, 11, 13, 17, 19, 23, 29],
                        memory_kb: 142.6,
                        mode: wasmState.isOffline ? 'Offline WASM Sandbox (IndexedDB synced)' : 'Client WebAssembly (0ms Server Latency)',
                        executions_total: wasmState.executionCount,
                    };
                }

                const html = renderWasmPlayground(wasmState);
                res.writeHead(200, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ id, html, state: wasmState, toasts: [{ message: 'Code executed in-browser via WASM!', type: 'success' }], success: true }));
                return;
            }

            if (id === 'cmp_aot') {
                if (action === 'runAotOptimization') {
                    aotState.isOptimized = true;
                    aotState.compilationReport = {
                        compiled_routes: 12,
                        container_services: 24,
                        pulsex_templates: 8,
                        bytecode_size_kb: 38.4,
                        cold_start_reduction: '98.4%',
                    };
                }
                if (action === 'spawnServerlessMicroVM') {
                    aotState.microvmMetrics.active_instances = (aotState.microvmMetrics.active_instances || 0) + 1;
                }

                const html = renderAotVisualizer(aotState);
                res.writeHead(200, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ id, html, state: aotState, toasts: [{ message: 'AOT optimization applied!', type: 'success' }], success: true }));
                return;
            }

            res.writeHead(400, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ error: 'Unknown component' }));
        });
        return;
    }

    if (pathname === '/' || pathname === '/index.html') {
        const content = getHomeContent();
        if (isSpa) {
            res.writeHead(200, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ url: '/', title: 'Pulse • Modern Reactive PHP Framework', html: content }));
        } else {
            res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
            res.end(getLayout(content, 'Pulse • Modern Reactive PHP Framework'));
        }
        return;
    }

    if (pathname === '/wasm') {
        const content = getWasmContent();
        if (isSpa) {
            res.writeHead(200, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ url: '/wasm', title: 'In-Browser WASM PHP • Pulse Framework', html: content }));
        } else {
            res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
            res.end(getLayout(content, 'In-Browser WASM PHP • Pulse Framework'));
        }
        return;
    }

    if (pathname === '/aot') {
        const content = getAotContent();
        if (isSpa) {
            res.writeHead(200, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ url: '/aot', title: 'AOT Compilation & Micro-VMs • Pulse Framework', html: content }));
        } else {
            res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
            res.end(getLayout(content, 'AOT Compilation & Micro-VMs • Pulse Framework'));
        }
        return;
    }

    if (pathname === '/upgrade') {
        const content = getUpgradeContent();
        if (isSpa) {
            res.writeHead(200, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ url: '/upgrade', title: 'Upgrade Plan & Quotas • Pulse Framework', html: content }));
        } else {
            res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
            res.end(getLayout(content, 'Upgrade Plan & Quotas • Pulse Framework'));
        }
        return;
    }

    if (pathname === '/agents') {
        const content = getAgentsContent();
        if (isSpa) {
            res.writeHead(200, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ url: '/agents', title: 'Autonomous Multi-Agent Mesh • Pulse Framework', html: content }));
        } else {
            res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
            res.end(getLayout(content, 'Autonomous Multi-Agent Mesh • Pulse Framework'));
        }
        return;
    }

    if (pathname === '/_pulse/studio') {
        const content = `
        <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 2rem 1rem;">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 2rem; background: rgba(21, 13, 36, 0.85); padding: 1.5rem 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border);">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #f43f5e, #a855f7); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #ffffff; box-shadow: 0 0 25px rgba(244, 63, 94, 0.6);">
                        <svg class="icon" style="stroke-width: 2.5; stroke: #ffffff; width: 24px; height: 24px;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    </div>
                    <div>
                        <h1 style="font-size: 1.6rem; font-weight: 800; display: flex; align-items: center; gap: 0.6rem; margin: 0;">
                            <span>PULSE STUDIO</span>
                            <span class="badge" style="background: rgba(244, 63, 94, 0.15); color: #fb7185;">v4.0.0 • Infinity</span>
                        </h1>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0.2rem 0 0 0;">In-Browser WASM Engine • AOT Opcode Cache • Micro-VM Serverless • AI Swarms</p>
                    </div>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="/wasm" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.4rem 0.9rem;">⚡ WASM Sandbox</a>
                    <a href="/aot" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.4rem 0.9rem;">🚀 AOT Cache</a>
                    <a href="/" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.4rem 0.9rem;">← Back</a>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.75rem;">
                <div class="card" style="border: 1px solid rgba(56, 189, 248, 0.3);">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-title-icon" style="background: rgba(56, 189, 248, 0.2); color: #38bdf8;">
                                <svg class="icon" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon></svg>
                            </div>
                            <span>In-Browser WASM Engine</span>
                        </div>
                        <span class="badge" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8;">0ms Client</span>
                    </div>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">Client-side WebAssembly PHP JIT execution metrics:</p>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; background: rgba(0,0,0,0.35); padding: 0.75rem; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.82rem; color: #fff;">
                            <span>Heap Pages:</span><strong>256 (16 MB)</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.82rem; color: #fff;">
                            <span>Offline Sync:</span><strong style="color: #4ade80;">IndexedDB Vector Clock</strong>
                        </div>
                    </div>
                </div>

                <div class="card" style="border: 1px solid rgba(168, 85, 247, 0.3);">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-title-icon" style="background: rgba(168, 85, 247, 0.2); color: #c084fc;">
                                <svg class="icon" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                            </div>
                            <span>AOT Bytecode & Micro-VM</span>
                        </div>
                        <span class="badge" style="background: rgba(168, 85, 247, 0.15); color: #c084fc;">0.12ms Boot</span>
                    </div>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">Ahead-of-time pre-compiled static kernel bundles:</p>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; background: rgba(0,0,0,0.35); padding: 0.75rem; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.82rem; color: #fff;">
                            <span>Micro-VM Snapshots:</span><strong style="color: #38bdf8;">1 Ready (0.38ms)</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.82rem; color: #fff;">
                            <span>Opcode Cache Hits:</span><strong style="color: #4ade80;">99.4%</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        if (isSpa) {
            res.writeHead(200, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ url: '/_pulse/studio', title: 'Pulse Studio • Developer Cockpit (v4.0)', html: content }));
        } else {
            res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
            res.end(getLayout(content, 'Pulse Studio • Developer Cockpit (v4.0)'));
        }
        return;
    }

    res.writeHead(404, { 'Content-Type': 'text/html' });
    res.end('<h1>404 Not Found</h1>');
});

function timeNow() {
    return Math.floor(Date.now() / 1000);
}

server.listen(PORT, () => {
    console.log(`⚡ Pulse PHP Framework v4.0.0 (Infinity) Server listening on http://localhost:${PORT}`);
});
