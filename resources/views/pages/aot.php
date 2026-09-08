<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <!-- Hero Header -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <span class="badge" style="background: rgba(168, 85, 247, 0.15); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.4); margin-bottom: 1rem;">
            ⚡ Pulse v4.0 (Infinity) AOT & Serverless Micro-VMs
        </span>
        <h1 style="font-size: 2.5rem; font-weight: 800; color: #fff; margin-bottom: 0.75rem;">
            Ahead-of-Time Bytecode & Micro-VMs
        </h1>
        <p style="font-size: 1.1rem; color: var(--text-muted); max-width: 750px; margin: 0 auto;">
            Pre-compile routes, dependency graphs, and templates into static binary opcode bundles. Resurrect serverless micro-VM containers in under 0.4ms.
        </p>
    </div>

    <!-- Live AOT Visualizer Component -->
    <div class="glass-card" style="padding: 2rem; margin-bottom: 3rem; border: 1px solid rgba(168, 85, 247, 0.3);">
        <?= component(\App\Components\AotVisualizer::class) ?>
    </div>

    <!-- Architectural Highlights -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        <div class="glass-card" style="padding: 1.5rem;">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">⚡</div>
            <h4 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0 0 0.5rem 0;">0.12ms Cold Boot</h4>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.6;">
                Eliminates runtime route compiling, DI reflection, and template parsing during bootstrap.
            </p>
        </div>

        <div class="glass-card" style="padding: 1.5rem;">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">💾</div>
            <h4 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0 0 0.5rem 0;">Memory Snapshotting</h4>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.6;">
                Micro-VM snapshots save warm framework memory state to disk and restore execution context instantly upon incoming traffic.
            </p>
        </div>

        <div class="glass-card" style="padding: 1.5rem;">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">📉</div>
            <h4 style="font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0 0 0.5rem 0;">Scale-to-Zero Efficiency</h4>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.6;">
                Instances scale to zero in 0.05ms when idle, reducing idle infrastructure costs by up to 90%.
            </p>
        </div>
    </div>
</div>
