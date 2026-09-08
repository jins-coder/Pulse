<div class="subscription-upgrade-component">
    <!-- Header & Billing Switcher -->
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 2rem;">
        <div>
            <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--accent-cyan); font-weight: 700; letter-spacing: 0.08em; margin-bottom: 0.25rem;">
                Tenant Subscription Cockpit
            </div>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: #fff; margin: 0;">
                Upgrade Account Plan & Subsystems
            </h2>
        </div>

        <!-- Frequency Toggle -->
        <div style="display: flex; align-items: center; gap: 0.5rem; background: rgba(15, 23, 42, 0.9); border: 1px solid var(--border); padding: 4px; border-radius: 9999px;">
            <button action="setPeriod('monthly')" class="btn <?= $billingPeriod === 'monthly' ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: 9999px; padding: 0.4rem 1.1rem; font-size: 0.85rem;">
                Monthly Billing
            </button>
            <button action="setPeriod('annual')" class="btn <?= $billingPeriod === 'annual' ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: 9999px; padding: 0.4rem 1.1rem; font-size: 0.85rem; display: flex; align-items: center; gap: 0.4rem;">
                <span>Annual Billing</span>
                <span class="badge" style="background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.4); font-size: 0.7rem; padding: 1px 6px;">SAVE 20%</span>
            </button>
        </div>
    </div>

    <?php if (!empty($successMessage)): ?>
        <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.4); color: #4ade80; padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
            <svg class="icon" style="width: 20px; height: 20px;" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <div style="font-weight: 600;"><?= e($successMessage) ?></div>
        </div>
    <?php endif; ?>

    <!-- Current Usage Quotas -->
    <div class="glass-card" style="padding: 1.25rem 1.5rem; margin-bottom: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; border: 1px solid rgba(255,255,255,0.06);">
        <div>
            <div style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; margin-bottom: 0.35rem;">Active Tenant Plan</div>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.4rem; font-weight: 800; color: #38bdf8;"><?= strtoupper(e($currentTier)) ?></span>
                <span class="badge" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8; font-size: 0.75rem;">Active</span>
            </div>
        </div>
        <div>
            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.35rem;">
                <span>AI Agent Mesh Invocations</span>
                <span style="font-family: 'JetBrains Mono'; font-weight: 600; color: #fff;"><?= e($usage['agent_runs']) ?> / 50 (84%)</span>
            </div>
            <div style="height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                <div style="width: 84%; height: 100%; background: linear-gradient(90deg, #38bdf8, #f43f5e);"></div>
            </div>
        </div>
        <div>
            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.35rem;">
                <span>Queue & Fiber Throughput</span>
                <span style="font-family: 'JetBrains Mono'; font-weight: 600; color: #fff;"><?= e($usage['queue_jobs']) ?> / 5,000 (82%)</span>
            </div>
            <div style="height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden;">
                <div style="width: 82%; height: 100%; background: linear-gradient(90deg, #38bdf8, #818cf8);"></div>
            </div>
        </div>
    </div>

    <!-- Tier Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <?php foreach ($tiers as $tierId => $tier): 
            $isSelected = ($selectedTier === $tierId);
            $isCurrent = ($currentTier === $tierId);
            $price = ($billingPeriod === 'annual') ? $tier['annual_price'] : $tier['monthly_price'];
        ?>
            <div class="glass-card" style="padding: 1.5rem; position: relative; display: flex; flex-direction: column; justify-content: space-between; border: 2px solid <?= $isSelected ? '#38bdf8' : ($isCurrent ? 'rgba(56, 189, 248, 0.3)' : 'rgba(255,255,255,0.05)') ?>; background: <?= $isSelected ? 'rgba(56, 189, 248, 0.06)' : 'rgba(15, 23, 42, 0.6)' ?>; border-radius: 16px; transition: all 0.2s ease;">
                <?php if ($tier['badge']): ?>
                    <div style="position: absolute; top: -12px; right: 20px;">
                        <span class="badge" style="background: <?= $tier['color'] ?>; color: #0f172a; font-weight: 800; font-size: 0.75rem; padding: 2px 10px; border-radius: 9999px;">
                            <?= e($tier['badge']) ?>
                        </span>
                    </div>
                <?php endif; ?>

                <div>
                    <h3 style="font-size: 1.3rem; font-weight: 800; color: #fff; margin: 0 0 0.4rem 0;">
                        <?= e($tier['name']) ?>
                    </h3>
                    <p style="font-size: 0.85rem; color: var(--text-muted); min-height: 40px; margin: 0 0 1.25rem 0;">
                        <?= e($tier['tagline']) ?>
                    </p>

                    <!-- Pricing -->
                    <div style="margin-bottom: 1.5rem; display: flex; align-items: baseline; gap: 0.25rem;">
                        <span style="font-size: 2.5rem; font-weight: 800; font-family: 'JetBrains Mono', monospace; color: <?= $tier['color'] ?>;">
                            $<?= e($price) ?>
                        </span>
                        <span style="color: var(--text-muted); font-size: 0.9rem;">/ month</span>
                    </div>

                    <!-- Quota Limits -->
                    <div style="background: rgba(0,0,0,0.25); padding: 0.85rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.82rem; border: 1px solid rgba(255,255,255,0.03);">
                        <div style="font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase; font-size: 0.7rem;">Included Resource Quotas</div>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.35rem;">
                            <?php foreach ($tier['limits'] as $limitKey => $limitVal): ?>
                                <li style="display: flex; justify-content: space-between; color: var(--text-main);">
                                    <span style="color: var(--text-muted);"><?= ucwords(str_replace('_', ' ', $limitKey)) ?></span>
                                    <strong style="font-family: 'JetBrains Mono';"><?= e($limitVal) ?></strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Features Checklist -->
                    <div style="font-size: 0.82rem; margin-bottom: 1.5rem;">
                        <div style="font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem; text-transform: uppercase; font-size: 0.7rem;">Subsystems & Capabilities</div>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.4rem;">
                            <?php foreach ($tier['features'] as $feature => $enabled): ?>
                                <li style="display: flex; align-items: center; gap: 0.5rem; color: <?= $enabled ? '#f8fafc' : 'rgba(255,255,255,0.3)' ?>;">
                                    <?php if ($enabled): ?>
                                        <svg class="icon" style="width: 14px; height: 14px; color: #4ade80; flex-shrink: 0;" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <?php else: ?>
                                        <svg class="icon" style="width: 14px; height: 14px; color: rgba(255,255,255,0.2); flex-shrink: 0;" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    <?php endif; ?>
                                    <span><?= e($feature) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Select Tier Button -->
                <div>
                    <?php if ($isCurrent): ?>
                        <button class="btn btn-secondary" disabled style="width: 100%; opacity: 0.7; font-weight: 700;">
                            ✓ Current Plan
                        </button>
                    <?php else: ?>
                        <button action="selectTier('<?= e($tierId) ?>')" class="btn <?= $isSelected ? 'btn-primary' : 'btn-secondary' ?>" style="width: 100%; font-weight: 700;">
                            <?= $isSelected ? 'Selected' : 'Select ' . e($tier['name']) ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Upgrade Action Footer Bar -->
    <?php if ($selectedTier !== $currentTier): ?>
        <div class="glass-card" style="padding: 1.5rem 2rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.5rem; border: 1px solid #38bdf8; background: rgba(56, 189, 248, 0.08); border-radius: 16px;">
            <div>
                <div style="font-size: 0.85rem; color: var(--accent-cyan); font-weight: 700;">Ready to Upgrade?</div>
                <div style="font-size: 1.15rem; font-weight: 700; color: #fff;">
                    Upgrading to <strong style="color: #38bdf8;"><?= e($tiers[$selectedTier]['name']) ?></strong> (<?= e(ucfirst($billingPeriod)) ?>)
                </div>
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.2rem;">
                    Instant access to AI Multi-Agent Mesh, Self-Healing Queues, and Distributed Tracing.
                </div>
            </div>

            <button action="upgradePlan" class="btn btn-primary" style="padding: 0.85rem 2.2rem; font-size: 1.05rem; font-weight: 800; border-radius: 10px; box-shadow: 0 10px 25px -5px rgba(56, 189, 248, 0.5);">
                ⚡ Confirm & Instant Upgrade
            </button>
        </div>
    <?php endif; ?>
</div>
