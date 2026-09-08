<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <!-- Hero Header -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <span class="badge" style="background: rgba(168, 85, 247, 0.15); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.4); margin-bottom: 1rem;">
            ⚡ Pulse v3.0 (Horizon) Autonomous Subsystem
        </span>
        <h1 style="font-size: 2.5rem; font-weight: 800; color: #fff; margin-bottom: 0.75rem;">
            Autonomous AI Multi-Agent Mesh
        </h1>
        <p style="font-size: 1.1rem; color: var(--text-muted); max-width: 700px; margin: 0 auto;">
            Collaborative swarms of specialized PHP agents running concurrently on Fibers, sharing a distributed blackboard memory, and coordinating tool calls.
        </p>
    </div>

    <!-- Live Interactive Agent Mesh Component -->
    <div class="glass-card" style="padding: 2rem; margin-bottom: 3rem; border: 1px solid rgba(168, 85, 247, 0.3);">
        <?= component(\App\Components\AgentMeshVisualizer::class) ?>
    </div>

    <!-- Agent Architecture Pillars -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        <div class="glass-card" style="padding: 1.5rem;">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🧠</div>
            <h4 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0 0 0.5rem 0;">Shared Blackboard Memory</h4>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.6;">
                Agents write intermediate artifacts, hypotheses, and execution results into a unified memory blackboard accessible by all mesh workers.
            </p>
        </div>

        <div class="glass-card" style="padding: 1.5rem;">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🛠️</div>
            <h4 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0 0 0.5rem 0;">Type-Safe #[AiTool] Invocation</h4>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.6;">
                Tools are declared with PHP 8.2+ attributes on standard methods. Pulse auto-generates JSON schemas for OpenAI, Anthropic, Gemini, and Ollama.
            </p>
        </div>

        <div class="glass-card" style="padding: 1.5rem;">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">⚡</div>
            <h4 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0 0 0.5rem 0;">Fiber Concurrency</h4>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.6;">
                Agent reasoning steps and asynchronous tool executions run on non-blocking native PHP Fibers with zero I/O blocking overhead.
            </p>
        </div>
    </div>
</div>
