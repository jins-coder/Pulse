<div class="user-search-component">
    <!-- Search Bar with Search Icon -->
    <div style="position: relative; margin-bottom: 1rem;">
        <div style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); display: flex; align-items: center; pointer-events: none;">
            <svg class="icon" style="width: 16px; height: 16px;" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </div>

        <input 
            type="text" 
            bind="query" 
            value="<?= e($query) ?>" 
            debounce="150" 
            placeholder="Type to filter team members..." 
            class="input-text"
        >

        <?php if (!empty($query)): ?>
            <button 
                action="clearSearch" 
                title="Clear Search"
                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.08); border: none; border-radius: 4px; padding: 4px; color: var(--text-muted); cursor: pointer; display: flex; align-items: center; justify-content: center;"
            >
                <svg class="icon" style="width: 14px; height: 14px;" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        <?php endif; ?>
    </div>

    <!-- Category Filter Tabs -->
    <div style="display: flex; gap: 0.5rem; margin-bottom: 1.25rem; overflow-x: auto; padding-bottom: 4px;">
        <?php foreach (['All', 'Engineering', 'Design', 'Operations', 'Management'] as $cat): ?>
            <button 
                action="setCategory('<?= e($cat) ?>')" 
                class="btn <?= $selectedCategory === $cat ? 'btn-primary' : 'btn-secondary' ?>" 
                style="font-size: 0.8rem; padding: 0.35rem 0.75rem; border-radius: 9999px;"
            >
                <?= e($cat) ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Results List -->
    <div style="background: rgba(0, 0, 0, 0.25); border-radius: var(--radius-md); padding: 0.5rem; min-height: 180px; max-height: 240px; overflow-y: auto; border: 1px solid rgba(255, 255, 255, 0.04);">
        <div style="font-size: 0.75rem; color: var(--text-muted); padding: 0.4rem 0.6rem; text-transform: uppercase; letter-spacing: 0.06em; display: flex; justify-content: space-between; font-weight: 700;">
            <span style="display: flex; align-items: center; gap: 0.35rem;">
                <svg class="icon" style="width: 13px; height: 13px;" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <span>Found <?= e($total) ?> Members</span>
            </span>
            <span>Category: <?= e($selectedCategory) ?></span>
        </div>

        <?php if (empty($results)): ?>
            <div style="text-align: center; padding: 2rem 1rem; color: var(--text-muted); font-size: 0.9rem;">
                No team members matched "<strong><?= e($query) ?></strong>" in <?= e($selectedCategory) ?>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                <?php foreach ($results as $u): ?>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0.75rem; background: rgba(255, 255, 255, 0.03); border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.04);">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 1.25rem;"><?= $u['avatar'] ?></span>
                            <div>
                                <div style="font-weight: 700; font-size: 0.9rem; color: var(--text-main);"><?= e($u['name']) ?></div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?= e($u['role']) ?></div>
                            </div>
                        </div>
                        <span class="badge" style="font-size: 0.7rem;"><?= e($u['category']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
