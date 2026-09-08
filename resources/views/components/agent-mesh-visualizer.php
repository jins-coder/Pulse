<div class="agent-mesh-visualizer">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <div style="font-size: 0.8rem; text-transform: uppercase; color: #a855f7; font-weight: 700; letter-spacing: 0.08em;">
                Autonomous Multi-Agent Mesh
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0;">
                Live Swarm Orchestration Engine
            </h3>
        </div>

        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <span class="badge" style="background: rgba(168, 85, 247, 0.15); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.4); font-size: 0.75rem;">
                <?= count($workers) ?> Active Workers
            </span>
            <span class="badge" style="background: rgba(34, 197, 94, 0.15); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.4); font-size: 0.75rem;">
                <?= e($status) ?>
            </span>
        </div>
    </div>

    <!-- Workers Nodes Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <?php foreach ($workers as $w): ?>
            <div class="glass-card" style="padding: 1rem 1.25rem; border: 1px solid rgba(168, 85, 247, 0.2); background: rgba(168, 85, 247, 0.04); border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                    <div>
                        <div style="font-weight: 700; color: #fff; font-size: 0.95rem;"><?= e($w->name) ?></div>
                        <div style="font-size: 0.75rem; color: #c084fc;"><?= e($w->role) ?></div>
                    </div>
                    <span class="badge" style="font-size: 0.65rem; background: rgba(0,0,0,0.4); color: var(--text-muted); font-family: 'JetBrains Mono';"><?= e($w->id) ?></span>
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.75rem;">
                    <?= e($w->systemPrompt) ?>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                    <?php foreach ($w->tools as $tool): ?>
                        <span class="badge" style="font-size: 0.65rem; background: rgba(56, 189, 248, 0.1); color: #38bdf8; font-family: 'JetBrains Mono';">
                            #[AiTool] <?= e($tool) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Goal Input & Trigger -->
    <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.06); padding: 1.25rem; border-radius: 12px; margin-bottom: 1.5rem;">
        <label style="display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem; font-weight: 600;">
            Swarm Orchestration Goal / Directive
        </label>
        <div style="display: flex; gap: 0.75rem;">
            <input type="text" bind="goal" value="<?= e($goal) ?>" style="flex: 1; padding: 0.65rem 1rem; background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border); border-radius: 8px; color: #fff; font-size: 0.9rem;">
            <button action="runSwarm" class="btn btn-primary" style="padding: 0.65rem 1.5rem; font-weight: 700; background: linear-gradient(135deg, #a855f7, #6366f1); border: none; display: flex; align-items: center; gap: 0.5rem;">
                <svg class="icon" style="width: 16px; height: 16px;" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                <span>Dispatch Swarm</span>
            </button>
        </div>
    </div>

    <!-- Swarm Execution Logs -->
    <?php if (!empty($swarmLog)): ?>
        <div style="background: rgba(0,0,0,0.4); border: 1px solid rgba(168, 85, 247, 0.3); padding: 1.25rem; border-radius: 12px;">
            <div style="font-size: 0.8rem; text-transform: uppercase; color: #c084fc; font-weight: 700; margin-bottom: 0.75rem;">
                Swarm Execution Trace
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.6rem; font-family: 'JetBrains Mono', monospace; font-size: 0.8rem;">
                <?php foreach ($swarmLog as $log): ?>
                    <div style="display: flex; gap: 0.75rem; padding: 0.5rem; background: rgba(255,255,255,0.02); border-radius: 6px;">
                        <span style="color: #38bdf8;">[<?= e($log['agent']) ?>]</span>
                        <span style="color: #e2e8f0;"><?= e($log['message'] ?? $log['result']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($latestSynthesis): ?>
                <div style="margin-top: 1rem; padding: 0.75rem 1rem; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; color: #4ade80; font-size: 0.85rem; font-weight: 600;">
                    ✓ Synthesis: <?= e($latestSynthesis) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
