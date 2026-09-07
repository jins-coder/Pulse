<div class="analytics-widget">
    <div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <div>
            <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; font-weight: 700;">
                Live Revenue Metrics (<?= e($period) ?>)
            </div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--accent-cyan); font-family: 'JetBrains Mono', monospace;">
                $<?= e($total) ?>k <span style="font-size: 0.85rem; color: #4ade80; font-weight: 600;">+18.4%</span>
            </div>
        </div>

        <div style="display: flex; gap: 0.4rem; background: rgba(0,0,0,0.3); padding: 4px; border-radius: 8px;">
            <button action="setPeriod('daily')" class="btn <?= $period === 'daily' ? 'btn-primary' : 'btn-secondary' ?>" style="font-size: 0.75rem; padding: 0.3rem 0.6rem;">Daily</button>
            <button action="setPeriod('weekly')" class="btn <?= $period === 'weekly' ? 'btn-primary' : 'btn-secondary' ?>" style="font-size: 0.75rem; padding: 0.3rem 0.6rem;">Weekly</button>
            <button action="setPeriod('monthly')" class="btn <?= $period === 'monthly' ? 'btn-primary' : 'btn-secondary' ?>" style="font-size: 0.75rem; padding: 0.3rem 0.6rem;">Monthly</button>
        </div>
    </div>

    <!-- Client-side Canvas Chart Target -->
    <div style="position: relative; width: 100%; height: 160px; background: rgba(0,0,0,0.25); border-radius: 12px; padding: 10px; border: 1px solid rgba(255,255,255,0.05);">
        <canvas id="pulse-chart" width="480" height="140" style="width: 100%; height: 100%; display: block;"></canvas>
    </div>

    <div style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.85rem; color: var(--text-muted);">
        <span>Average: <strong style="color: var(--text-main); font-family: 'JetBrains Mono';">$<?= e($avg) ?>k</strong></span>
        <span style="color: var(--accent-cyan); display: flex; align-items: center; gap: 5px; font-weight: 600;">
            <svg class="icon" style="width: 13px; height: 13px;" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
            <span>Client JS Canvas + PHP Server State</span>
        </span>
    </div>

    <!-- Co-located Scoped Client Script -->
    <script type="pulse/client">
        return {
            mounted(el, wire) {
                const canvas = el.querySelector('#pulse-chart');
                if (!canvas) return;
                
                const data = <?= json_encode($data) ?>;
                this.drawChart(canvas, data);

                wire.on('updated', (state) => {
                    this.drawChart(canvas, state.data || data);
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
</div>
