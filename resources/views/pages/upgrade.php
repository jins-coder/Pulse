<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <!-- SaaS Multi-Tenant Subscription Component -->
    <?= component(\App\Components\SubscriptionUpgrade::class) ?>

    <!-- FAQ & Enterprise Architecture Highlights -->
    <div style="margin-top: 4rem; padding-top: 3rem; border-top: 1px solid rgba(255,255,255,0.06);">
        <h3 style="font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 2rem; text-align: center;">
            Frequently Asked Architecture & Billing Questions
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
            <div class="glass-card" style="padding: 1.5rem;">
                <h4 style="font-size: 1.05rem; font-weight: 700; color: #38bdf8; margin: 0 0 0.5rem 0;">
                    How does Multi-Tenant Context Scoping work?
                </h4>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.6;">
                    Pulse isolates tenant data natively using <code>TenantContext::setTenantId()</code>. Database queries, CRDT edge replication channels, and background job queues are automatically partitioned per tenant.
                </p>
            </div>

            <div class="glass-card" style="padding: 1.5rem;">
                <h4 style="font-size: 1.05rem; font-weight: 700; color: #38bdf8; margin: 0 0 0.5rem 0;">
                    Can I dynamically scale AI Agent Swarms?
                </h4>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.6;">
                    Yes! Pro and Horizon plans support autonomous agent worker scaling. Supervisor agents distribute sub-tasks across worker pools using non-blocking PHP Fibers with zero cold-boot delay.
                </p>
            </div>

            <div class="glass-card" style="padding: 1.5rem;">
                <h4 style="font-size: 1.05rem; font-weight: 700; color: #38bdf8; margin: 0 0 0.5rem 0;">
                    How does Self-Healing Queue remediation work?
                </h4>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; line-height: 1.6;">
                    When jobs fail due to transient network conditions or timeouts, Pulse's DLQ classifier diagnoses the failure signature and automatically triggers parameter adjustment or retry backoff.
                </p>
            </div>
        </div>
    </div>
</div>
