<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Pulse PHP Application Framework') ?></title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Laravel-Inspired 2-Color Light Palette */
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --border: #e2e8f0;
            --border-hover: #cbd5e1;
            
            /* Two Core Brand Colors: Laravel Coral Red + Deep Obsidian Slate */
            --accent-red: #ff2d20;
            --accent-red-hover: #e02417;
            --accent-red-light: #fef2f2;
            --accent-slate: #0f172a;
            
            --text-main: #0f172a;
            --text-muted: #64748b;
            --text-subtle: #94a3b8;
            
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.07), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.04);
            
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
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
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* Top Clean Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.9rem 2rem;
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: var(--shadow-sm);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            color: var(--text-main);
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.02em;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            background: var(--accent-red);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }

        .brand-badge {
            background: var(--accent-red-light);
            border: 1px solid #fee2e2;
            color: var(--accent-red);
            padding: 0.15rem 0.5rem;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
        }

        .nav-links {
            display: flex;
            gap: 1.25rem;
            align-items: center;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.9rem;
            transition: color 0.15s ease;
            padding: 0.35rem 0.6rem;
            border-radius: var(--radius-sm);
        }

        .nav-link:hover, .nav-link.active {
            color: var(--accent-red);
            background: var(--accent-red-light);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 5rem;
        }

        /* Hero Header */
        .hero {
            text-align: center;
            padding: 3rem 1rem 2rem;
            max-width: 820px;
            margin: 0 auto;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.9rem;
            background: var(--accent-red-light);
            border: 1px solid #fecaca;
            border-radius: 9999px;
            color: var(--accent-red);
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.18;
            margin-bottom: 1rem;
            color: var(--text-main);
        }

        .hero h1 span {
            color: var(--accent-red);
        }

        .hero p {
            font-size: 1.125rem;
            color: var(--text-muted);
            line-height: 1.65;
        }

        /* Clean White Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.75rem;
            box-shadow: var(--shadow-sm);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            border-color: var(--border-hover);
            box-shadow: var(--shadow-md);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .card-title-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--accent-red-light);
            color: var(--accent-red);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            border-radius: 9999px;
            font-weight: 700;
            background: #f1f5f9;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            border: 1px solid var(--border);
        }

        /* Clean Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--accent-red);
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(255, 45, 32, 0.3);
        }

        .btn-primary:hover {
            background: var(--accent-red-hover);
            box-shadow: 0 4px 12px rgba(255, 45, 32, 0.4);
        }

        .btn-secondary {
            background: #ffffff;
            color: var(--text-main);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: var(--border-hover);
        }

        .input-text {
            width: 100%;
            padding: 0.65rem 1rem;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text-main);
            font-size: 0.9rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .input-text:focus {
            border-color: var(--accent-red);
            box-shadow: 0 0 0 3px rgba(255, 45, 32, 0.15);
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

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
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
            <span class="brand-badge">v4.0.0</span>
        </a>
        <div class="nav-links">
            <a href="/" class="nav-link">
                <svg class="icon" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 22"></polyline></svg>
                <span>Home</span>
            </a>
            <a href="/docs" class="nav-link" style="color: var(--accent-red); font-weight: 700;">
                <svg class="icon" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                <span>Docs</span>
            </a>
            <a href="/beast" class="nav-link">
                <svg class="icon" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span>Beast Core</span>
            </a>
            <a href="/wasm" class="nav-link">
                <svg class="icon" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                <span>WASM</span>
            </a>
            <a href="/aot" class="nav-link">
                <svg class="icon" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                <span>AOT & Micro-VM</span>
            </a>
            <a href="/agents" class="nav-link">
                <svg class="icon" viewBox="0 0 24 24"><path d="M12 2a8 8 0 0 0-8 8c0 3.36 2.07 6.24 5 7.42V20a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2.58c2.93-1.18 5-4.06 5-7.42a8 8 0 0 0-8-8z"></path></svg>
                <span>AI Agents</span>
            </a>
            <a href="/upgrade" class="nav-link">
                <svg class="icon" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span>Upgrade</span>
            </a>
            <a href="/_pulse/studio" class="nav-link">
                <svg class="icon" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                <span>Studio</span>
            </a>
        </div>
    </nav>

    <main id="app">
        <?= $content ?? '' ?>
    </main>

    <script src="/pulse.js"></script>
</body>
</html>
