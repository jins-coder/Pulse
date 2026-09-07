<?php $this->extends('layout'); ?>

<div class="container" style="max-width: 1300px; padding-top: 1rem;">
    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 2.5rem; align-items: start;">
        
        <!-- Left Sidebar (Laravel-style Sticky Doc Index) -->
        <aside style="position: sticky; top: 90px; background: rgba(21, 13, 36, 0.75); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.5rem; backdrop-filter: blur(16px);">
            <div style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #fb7185; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg class="icon" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span>Documentation</span>
            </div>

            <nav style="display: flex; flex-direction: column; gap: 0.25rem;">
                <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-top: 0.75rem; margin-bottom: 0.25rem; text-transform: uppercase;">Getting Started</div>
                <a href="#quickstart" class="nav-link" style="padding: 0.35rem 0.5rem; border-radius: 6px; font-size: 0.88rem;">🚀 30s Quick Start</a>
                <a href="#routing" class="nav-link" style="padding: 0.35rem 0.5rem; border-radius: 6px; font-size: 0.88rem;">🔄 Tri-Mode Routing</a>

                <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-top: 0.75rem; margin-bottom: 0.25rem; text-transform: uppercase;">Architecture</div>
                <a href="#pulsex" class="nav-link" style="padding: 0.35rem 0.5rem; border-radius: 6px; font-size: 0.88rem;">🧩 PulseX Components</a>
                <a href="#database" class="nav-link" style="padding: 0.35rem 0.5rem; border-radius: 6px; font-size: 0.88rem;">🏢 Database & Tenancy</a>
                <a href="#reactor" class="nav-link" style="padding: 0.35rem 0.5rem; border-radius: 6px; font-size: 0.88rem;">⚡ Fiber Reactor</a>
                <a href="#ai-agents" class="nav-link" style="padding: 0.35rem 0.5rem; border-radius: 6px; font-size: 0.88rem;">🤖 AI Tool-Calling</a>

                <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin-top: 0.75rem; margin-bottom: 0.25rem; text-transform: uppercase;">Tooling</div>
                <a href="#artisan-cli" class="nav-link" style="padding: 0.35rem 0.5rem; border-radius: 6px; font-size: 0.88rem;">🛠️ Artisan-Style CLI</a>
                <a href="#studio" class="nav-link" style="padding: 0.35rem 0.5rem; border-radius: 6px; font-size: 0.88rem;">🎛️ Pulse Studio Cockpit</a>
            </nav>
        </aside>

        <!-- Right Main Content Area (Laravel-style Rich Doc Articles) -->
        <article style="min-width: 0;">

            <!-- Header Banner -->
            <div style="margin-bottom: 2.5rem;">
                <div class="hero-badge" style="margin-bottom: 1rem;">
                    <span>Official Pulse Documentation • v2.0.0 (Quantum)</span>
                </div>
                <h1 style="font-size: 2.5rem; font-weight: 800; line-height: 1.2; margin-bottom: 1rem;">
                    Pulse Framework Documentation
                </h1>
                <p style="font-size: 1.15rem; color: var(--text-muted); line-height: 1.7;">
                    A comprehensive, developer-first guide to building lightning-fast reactive web applications with pure PHP.
                </p>
            </div>

            <!-- Section 1: Quickstart -->
            <section id="quickstart" class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg class="icon" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                        </div>
                        <span>30-Second Quick Start</span>
                    </div>
                    <span class="badge">Getting Started</span>
                </div>
                <p style="color: var(--text-muted); margin-bottom: 1rem;">
                    Install dependencies and launch the persistent Fiber Reactor server:
                </p>
                <div style="background: rgba(0,0,0,0.45); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1rem 1.25rem; font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; color: #fb7185; margin-bottom: 1rem;">
                    <div style="color: var(--text-muted);"># 1. Install via Composer</div>
                    composer install<br><br>
                    <div style="color: var(--text-muted);"># 2. Launch Persistent In-Memory Reactor Server</div>
                    php bin/pulse serve --reactor 127.0.0.1:8000
                </div>
            </section>

            <!-- Section 2: Universal Routing -->
            <section id="routing" class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 22"></polyline></svg>
                        </div>
                        <span>Tri-Mode Universal Routing</span>
                    </div>
                    <span class="badge">routes/web.php</span>
                </div>
                <p style="color: var(--text-muted); margin-bottom: 1rem;">
                    Every route in Pulse seamlessly responds with full SSR HTML for first page loads, lightweight JSON morph diffs for SPA links, or standard JSON for API clients:
                </p>
                <pre style="background: rgba(0,0,0,0.45); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem; font-family: 'JetBrains Mono', monospace; font-size: 0.88rem; color: #fdf4ff; overflow-x: auto;"><code>use Pulse\Http\Response;

$router->get('/projects', function ($request) {
    $projects = Project::all();

    return Response::view('pages/projects', [
        'projects' => $projects
    ]);
});</code></pre>
            </section>

            <!-- Section 3: PulseX Single-File Components -->
            <section id="pulsex" class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M16.5 9.4 7.55 4.24a1.78 1.78 0 0 0-2.5 1.55v12.42a1.78 1.78 0 0 0 2.5 1.55L16.5 14.6a1.78 1.78 0 0 0 0-3.2z"></path></svg>
                        </div>
                        <span>PulseX Single-File Components (`.pulse`)</span>
                    </div>
                    <span class="badge">app/Components</span>
                </div>
                <p style="color: var(--text-muted); margin-bottom: 1rem;">
                    Single-file components co-locate PHP backend classes, JSX markup, and scoped client JavaScript in one file with automatic HMAC cryptographic signing:
                </p>
                <pre style="background: rgba(0,0,0,0.45); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: #fdf4ff; overflow-x: auto;"><code>&lt;!-- app/Components/Counter.pulse --&gt;
&lt;php&gt;
namespace App\Components;
use Pulse\Component\Component;

class Counter extends Component
{
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
        $this->toast("Counter incremented to {$this->count}");
    }
}
&lt;/php&gt;

&lt;div class="counter-box"&gt;
    &lt;h4&gt;Value: { $count }&lt;/h4&gt;
    &lt;button action="increment"&gt;+ Add&lt;/button&gt;
&lt;/div&gt;</code></pre>
            </section>

            <!-- Section 4: AI Agents -->
            <section id="ai-agents" class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                        </div>
                        <span>AI-Native Tool-Calling Agents (`#[AiTool]`)</span>
                    </div>
                    <span class="badge">src/AI/AiTool.php</span>
                </div>
                <p style="color: var(--text-muted); margin-bottom: 1rem;">
                    Decorate PHP classes to generate standardized LLM tool schemas for OpenAI, Anthropic, and Gemini:
                </p>
                <pre style="background: rgba(0,0,0,0.45); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: #fdf4ff; overflow-x: auto;"><code>namespace App\Services;
use Pulse\AI\AiTool;

class InventoryAgent
{
    #[AiTool(description: 'Retrieve real-time product stock level')]
    public function checkStock(int $productId): array
    {
        return ['productId' => $productId, 'stock' => 42];
    }
}</code></pre>
            </section>

            <!-- Section 5: Artisan CLI Reference -->
            <section id="artisan-cli" class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="card-title-icon">
                            <svg class="icon" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                        </div>
                        <span>Artisan-Style Developer CLI Reference</span>
                    </div>
                    <span class="badge">bin/pulse</span>
                </div>
                <div style="background: rgba(0,0,0,0.45); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1rem 1.25rem; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: #fdf4ff;">
                    <span style="color: #fb7185;">php bin/pulse serve --reactor</span>    # High-concurrency event-loop server<br>
                    <span style="color: #fb7185;">php bin/pulse make:component &lt;Name&gt; --pulse</span> # Create Single-File PulseX component<br>
                    <span style="color: #fb7185;">php bin/pulse make:migration &lt;Name&gt;</span> # Generate database migration<br>
                    <span style="color: #fb7185;">php bin/pulse db:migrate</span>            # Run database migrations<br>
                    <span style="color: #fb7185;">php bin/pulse routes</span>                # Display all registered routes
                </div>
            </section>

        </article>
    </div>
</div>
