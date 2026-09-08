<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Pulse PHP Application Framework') ?></title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
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
            --accent-amber: #fbbf24;
            --accent-glow: rgba(244, 63, 94, 0.22);
            --gradient-accent: linear-gradient(135deg, #f43f5e 0%, #a855f7 50%, #6366f1 100%);
            --gradient-warm: linear-gradient(135deg, #fbbf24 0%, #f43f5e 100%);
            --radius-md: 12px;
            --radius-lg: 18px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

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

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

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

        .nav-link:hover, .nav-link.active {
            color: #f43f5e;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 5rem;
        }

        .hero {
            text-align: center;
            padding: 3rem 1rem 2rem;
            max-width: 850px;
            margin: 0 auto;
        }

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

        .hero p {
            font-size: 1.15rem;
            color: var(--text-muted);
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 2rem;
            margin-top: 2.5rem;
        }

        .card {
            background: var(--bg-card);
            backdrop-filter: blur(14px);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.6);
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
            width: 30px;
            height: 30px;
            border-radius: 7px;
            background: rgba(244, 63, 94, 0.16);
            color: #fb7185;
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
            color: #fb7185;
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
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(244, 63, 94, 0.4);
        }

        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 22px rgba(244, 63, 94, 0.6);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-main);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.25);
        }

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
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input-text:focus {
            border-color: #f43f5e;
            box-shadow: 0 0 0 3px rgba(244, 63, 94, 0.25);
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

        [loading] { transition: opacity 0.2s ease; }
        .loading-active { opacity: 0.6; pointer-events: none; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="brand">
            <div class="brand-icon">
                <svg class="icon" style="stroke: #ffffff; stroke-width: 2.5;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            </div>
            <span>PULSE</span>
            <span class="brand-badge" style="background: rgba(244, 63, 94, 0.15); border-color: rgba(244, 63, 94, 0.4); color: #fb7185;">v4.0.0</span>
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
            <a href="/docs" class="nav-link">
                <svg class="icon" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                <span>Docs</span>
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
        <?= $content ?? '' ?>
    </main>

    <script src="/pulse.js"></script>
</body>
</html>
