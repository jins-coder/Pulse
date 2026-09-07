<div class="counter-component">
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 0.5rem; font-weight: 700;">
            Reactive Public State
        </div>
        <div style="font-size: 3.5rem; font-weight: 800; font-family: 'JetBrains Mono', monospace; color: var(--accent-cyan); line-height: 1; text-shadow: 0 0 25px rgba(56, 189, 248, 0.4);">
            <?= e($count) ?>
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
        <input type="number" bind="step" value="<?= e($step) ?>" min="1" max="100" style="width: 70px; padding: 0.35rem 0.5rem; background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border); border-radius: 6px; color: var(--text-main); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; text-align: center;">
    </div>
</div>
