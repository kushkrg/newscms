<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #000; }
    .header-actions { display: flex; gap: 0.5rem; align-items: center; }
    .btn { display: inline-block; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; border: 1px solid #000; cursor: pointer; transition: all 0.15s ease; }
    .btn-primary { background: #000; color: #fff; }
    .btn-primary:hover { background: #222; }
    .btn-sm { padding: 0.3rem 0.6rem; font-size: 0.8rem; }
    .btn-outline { background: #fff; color: #000; border: 1px solid #ccc; }
    .btn-outline:hover { border-color: #000; }
    .btn-danger { background: #fff; color: #000; border: 1px solid #999; }
    .btn-danger:hover { background: #000; color: #fff; }

    .stats-row { display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .stat-card { background: #fff; border: 1px solid #e5e5e5; padding: 1rem 1.5rem; flex: 1; min-width: 140px; }
    .stat-card__number { font-size: 1.75rem; font-weight: 700; color: #000; line-height: 1; }
    .stat-card__label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #666; margin-top: 0.25rem; }

    .filters-bar { display: flex; gap: 0.75rem; margin-bottom: 1.5rem; align-items: center; flex-wrap: wrap; }
    .filters-bar select,
    .filters-bar input[type="text"] { padding: 0.45rem 0.75rem; border: 1px solid #ccc; font-size: 0.85rem; background: #fff; color: #000; font-family: inherit; }
    .filters-bar select:focus,
    .filters-bar input:focus { outline: none; border-color: #000; }
    .filters-bar .btn { padding: 0.45rem 1rem; }

    .admin-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .admin-table th { text-align: left; padding: 0.75rem 1rem; border-bottom: 2px solid #000; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #333; }
    .admin-table td { padding: 0.75rem 1rem; border-bottom: 1px solid #e5e5e5; vertical-align: middle; }
    .admin-table tr:hover { background: #fafafa; }
    .admin-table a { color: #000; text-decoration: none; font-weight: 500; }
    .admin-table a:hover { text-decoration: underline; }

    .badge { display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-active { background: #dcfce7; color: #166534; }
    .badge-unsubscribed { background: #fee2e2; color: #991b1b; }

    .actions { display: flex; align-items: center; gap: 0.5rem; }
    .actions form { margin: 0; display: inline; }

    .pagination { display: flex; gap: 0.25rem; align-items: center; justify-content: center; margin-top: 2rem; }
    .pagination a,
    .pagination span { display: inline-block; padding: 0.4rem 0.75rem; font-size: 0.85rem; border: 1px solid #e5e5e5; text-decoration: none; color: #000; }
    .pagination a:hover { border-color: #000; background: #000; color: #fff; }
    .pagination .active { background: #000; color: #fff; border-color: #000; }
    .pagination .disabled { color: #ccc; pointer-events: none; }
    .pagination .ellipsis { border: none; padding: 0.4rem 0.25rem; color: #999; }

    .empty-state { text-align: center; padding: 3rem 1rem; color: #999; font-size: 0.95rem; }
    .subscriber-email { font-weight: 600; color: #000; }
    .subscriber-name { color: #666; font-size: 0.85rem; }
</style>

<div class="admin-header">
    <h1><?= h($pageTitle) ?></h1>
    <div class="header-actions">
        <a href="<?= url('admin/subscribers/email-config') ?>" class="btn btn-outline">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: -2px; margin-right: 4px;">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
            </svg>
            Email Config
        </a>
        <a href="<?= url('admin/subscribers/compose') ?>" class="btn btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: -2px; margin-right: 4px;">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
            </svg>
            Send Email
        </a>
    </div>
</div>

<!-- Stats -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-card__number"><?= (int) $totalCount ?></div>
        <div class="stat-card__label">Total Subscribers</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__number"><?= (int) $activeCount ?></div>
        <div class="stat-card__label">Active Subscribers</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__number"><?= (int) ($totalCount - $activeCount) ?></div>
        <div class="stat-card__label">Unsubscribed</div>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="<?= url('admin/subscribers') ?>" class="filters-bar">
    <select name="status">
        <option value="">All Status</option>
        <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
        <option value="unsubscribed" <?= ($filters['status'] ?? '') === 'unsubscribed' ? 'selected' : '' ?>>Unsubscribed</option>
    </select>
    <input type="text" name="search" placeholder="Search email or name..." value="<?= h($filters['search'] ?? '') ?>">
    <button type="submit" class="btn btn-outline">Filter</button>
    <?php if (!empty($filters['status']) || !empty($filters['search'])): ?>
        <a href="<?= url('admin/subscribers') ?>" class="btn btn-sm btn-outline">Clear</a>
    <?php endif; ?>
</form>

<?php if (!empty($subscribers)): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Status</th>
                <th>Subscribed</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subscribers as $sub): ?>
                <tr>
                    <td>
                        <span class="subscriber-email"><?= h($sub['email'] ?? '') ?></span>
                    </td>
                    <td>
                        <span class="subscriber-name"><?= h($sub['name'] ?? '—') ?></span>
                    </td>
                    <td>
                        <?php $status = $sub['status'] ?? 'active'; ?>
                        <span class="badge badge-<?= $status ?>"><?= ucfirst($status) ?></span>
                    </td>
                    <td>
                        <?php if (!empty($sub['subscribed_at'])): ?>
                            <?= h(date('M j, Y', strtotime($sub['subscribed_at']))) ?>
                        <?php else: ?>
                            <span style="color: #999;">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="<?= url('admin/subscribers/' . (int) $sub['id'] . '/edit') ?>" class="btn btn-sm btn-outline">Edit</a>
                        <form method="POST" action="<?= url('admin/subscribers/' . (int) $sub['id'] . '/delete') ?>" onsubmit="return confirm('Delete this subscriber?');">
                            <?= \App\Core\Csrf::field() ?>
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($paginator->hasPages()): ?>
        <div class="pagination">
            <?php if ($paginator->hasPrev()): ?>
                <a href="<?= url('admin/subscribers?page=' . $paginator->prevPage() . '&status=' . h($filters['status'] ?? '') . '&search=' . h($filters['search'] ?? '')) ?>">Prev</a>
            <?php else: ?>
                <span class="disabled">Prev</span>
            <?php endif; ?>

            <?php foreach ($paginator->pages() as $page): ?>
                <?php if ($page === '...'): ?>
                    <span class="ellipsis">...</span>
                <?php elseif ($page === $paginator->currentPage): ?>
                    <span class="active"><?= $page ?></span>
                <?php else: ?>
                    <a href="<?= url('admin/subscribers?page=' . $page . '&status=' . h($filters['status'] ?? '') . '&search=' . h($filters['search'] ?? '')) ?>"><?= $page ?></a>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($paginator->hasNext()): ?>
                <a href="<?= url('admin/subscribers?page=' . $paginator->nextPage() . '&status=' . h($filters['status'] ?? '') . '&search=' . h($filters['search'] ?? '')) ?>">Next</a>
            <?php else: ?>
                <span class="disabled">Next</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div class="empty-state">
        <p>No subscribers found.</p>
    </div>
<?php endif; ?>
