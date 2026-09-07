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
            --bg-primary: #090d16;
            --bg-secondary: #0f172a;
            --bg-card: rgba(30, 41, 59, 0.6);
            --border: rgba(255, 255, 255, 0.08);
            --border-hover: rgba(56, 189, 248, 0.4);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent-cyan: #38bdf8;
            --accent-indigo: #818cf8;
            --accent-glow: rgba(56, 189, 248, 0.15);
            --gradient-accent: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);
            --radius-md: 12px;
            --radius-lg: 18px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-main);
            min-height: 100vh;
            background-image: 
                radial-gradient(at 0% 0%, rgba(56, 189, 248, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(129, 140, 248, 0.08) 0px, transparent 50%);
            background-attachment: fixed;
            line-height: 1.6;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.1rem 2.5rem;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
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
            width: 32px;
            height: 32px;
            background: var(--gradient-accent);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #090d16;
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.5);
        }
        .brand-badge {
            background: rgba(56, 189, 248, 0.15);
            border: 1px solid rgba(56, 189, 248, 0.3);
            color: var(--accent-cyan);
            padding: 0.15rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
        }
        .nav-links { display: flex; gap: 1.5rem; align-items: center; }
        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s ease;
        }
        .nav-link:hover, .nav-link.active { color: var(--accent-cyan); }
        .container { max-width: 1200px; margin: 0 auto; padding: 2.5rem 1.5rem 5rem; }
        .hero { text-align: center; padding: 3rem 1rem 2rem; max-width: 850px; margin: 0 auto; }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.3);
            border-radius: 9999px;
            color: var(--accent-cyan);
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        .hero h1 {
            font-size: 3.25rem;
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
        .hero p { font-size: 1.15rem; color: var(--text-muted); }
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 2rem;
            margin-top: 2.5rem;
        }
        .card {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }
        .card:hover {
            border-color: var(--border-hover);
            transform: translateY(-2px);
            box-shadow: 0 25px 50px -12px var(--accent-glow);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .card-title-icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: rgba(56, 189, 248, 0.15);
            color: var(--accent-cyan);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            border-radius: 9999px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.08);
            color: var(--accent-cyan);
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
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
        .btn-primary {
            background: var(--gradient-accent);
            color: #090d16;
            box-shadow: 0 4px 15px rgba(56, 189, 248, 0.3);
        }
        .btn-primary:hover { transform: scale(1.02); }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-main);
            border: 1px solid var(--border);
        }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.15); }
        .input-text {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text-main);
            font-size: 0.95rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .input-text:focus {
            border-color: var(--accent-cyan);
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2);
        }
        .icon {
            width: 18px;
            height: 18px;
            stroke-width: 2;
            stroke: currentColor;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="brand">
            <div class="brand-icon">
                <svg class="icon" style="stroke: #090d16; stroke-width: 2.5;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            </div>
            <span>PULSE</span>
            <span class="brand-badge">v1.0.0</span>
        </a>
        <div class="nav-links">
            <a href="/" class="nav-link">
                <svg class="icon" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                <span>Home</span>
            </a>
            <a href="/about" class="nav-link">
                <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                <span>Architecture</span>
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

    <!-- Pulse Performance Profiler Bar with Icons -->
    <div id="pulse-profiler" style="position:fixed;bottom:0;left:0;right:0;background:rgba(9,13,22,0.92);backdrop-filter:blur(10px);border-top:1px solid rgba(56,189,248,0.3);color:#f8fafc;font-family:'JetBrains Mono',monospace;font-size:11px;padding:6px 16px;display:flex;gap:22px;align-items:center;z-index:99999;box-shadow:0 -4px 20px rgba(0,0,0,0.5);">
        <div style="font-weight:bold;color:#38bdf8;display:flex;align-items:center;gap:6px;">
            <svg style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            <span>PULSE v1.0.0</span>
        </div>
        <div style="display:flex;align-items:center;gap:5px;">
            <svg style="width:13px;height:13px;stroke:#4ade80;fill:none;stroke-width:2;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            <span>Time: <strong style="color:#4ade80;">1.24 ms</strong></span>
        </div>
        <div style="display:flex;align-items:center;gap:5px;">
            <svg style="width:13px;height:13px;stroke:#a78bfa;fill:none;stroke-width:2;" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
            <span>Memory: <strong style="color:#a78bfa;">1.62 MB</strong></span>
        </div>
        <div style="display:flex;align-items:center;gap:5px;">
            <svg style="width:13px;height:13px;stroke:#facc15;fill:none;stroke-width:2;" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
            <span>DB: <strong style="color:#facc15;">2 queries (0.28 ms)</strong></span>
        </div>
        <div style="display:flex;align-items:center;gap:5px;">
            <svg style="width:13px;height:13px;stroke:#38bdf8;fill:none;stroke-width:2;" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
            <span>Components: <strong style="color:#38bdf8;">2</strong></span>
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
                <span>v1.1.0 • PulseX Hybrid Template Engine • PHP + JSX + Client JS</span>
            </div>
            <h1>PHP stays PHP.<br><span>Pulse changes how it behaves.</span></h1>
            <p>A unified development model bringing reactive server-driven components, zero-build SPA navigation, fiber async concurrency, and co-located client JavaScript to standard PHP.</p>
        </div>

        <div class="grid-2">
            <!-- Analytics Widget Card -->
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
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">
                    This component authors PHP server business logic and client-side HTML5 canvas graphics in the <strong>exact same component</strong> with zero build pipeline.
                </p>
                ${renderAnalyticsWidget(analyticsState)}
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg class="icon" viewBox="0 0 24 24"><rect x="4" y="2" width="16" height="20" rx="2"></rect><line x1="8" y1="6" x2="16" y2="6"></line><line x1="16" y1="14" x2="16" y2="14.01"></line><line x1="12" y1="14" x2="12" y2="14.01"></line><line x1="8" y1="14" x2="8" y2="14.01"></line><line x1="16" y1="18" x2="16" y2="18.01"></line><line x1="12" y1="18" x2="12" y2="18.01"></line><line x1="8" y1="18" x2="8" y2="18.01"></line></svg>
                        </div>
                        <span>Reactive State Counter</span>
                    </div>
                    <span class="badge">
                        <svg class="icon" style="width: 12px; height: 12px;" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <span>HMAC Signed</span>
                    </span>
                </div>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">
                    Clicking buttons dispatches state mutations directly to the PHP class, seamlessly morphing the DOM with cryptographic state verification.
                </p>
                ${renderCounter(counterState)}
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg class="icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                        <span>Instant Live Search</span>
                    </div>
                    <span class="badge">
                        <svg class="icon" style="width: 12px; height: 12px;" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                        <span>Debounced Sync</span>
                    </span>
                </div>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">
                    Typing syncs server state with debouncing (150ms) and executes PHP filtering with zero manual API endpoints.
                </p>
                ${renderUserSearch(searchState)}
            </div>
        </div>
    </div>`;
}

function getAboutContent() {
    return `
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
            version: '1.0.0',
            codename: 'Helios',
            features: {
                tri_mode_routing: 'SSR ↔ SPA ↔ API',
                reactive_php_components: true,
                hmac_state_integrity: true,
                fiber_async_concurrency: true,
                multi_tenancy: true,
                realtime_channels: true,
                persistent_worker: true,
                profiler: true,
            },
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

    if (pathname === '/about') {
        const content = getAboutContent();
        if (isSpa) {
            res.writeHead(200, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ url: '/about', title: 'Architecture & Subsystems • Pulse PHP', html: content }));
        } else {
            res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
            res.end(getLayout(content, 'Architecture & Subsystems • Pulse PHP'));
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
    console.log(`⚡ Pulse PHP Framework v1.0.0 Server listening on http://localhost:${PORT}`);
});
