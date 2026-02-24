<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #000; }
    .btn { display: inline-block; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; border: 1px solid #000; cursor: pointer; transition: all 0.15s ease; }
    .btn-primary { background: #000; color: #fff; }
    .btn-primary:hover { background: #222; }
    .btn-sm { padding: 0.3rem 0.6rem; font-size: 0.8rem; }
    .btn-outline { background: #fff; color: #000; border: 1px solid #ccc; }
    .btn-outline:hover { border-color: #000; }
    .btn-danger { background: #fff; color: #000; border: 1px solid #999; }
    .btn-danger:hover { background: #000; color: #fff; }
    .admin-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .admin-table th { text-align: left; padding: 0.75rem 1rem; border-bottom: 2px solid #000; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #333; }
    .admin-table td { padding: 0.75rem 1rem; border-bottom: 1px solid #e5e5e5; vertical-align: middle; }
    .admin-table tr:hover { background: #fafafa; }
    .admin-table a { color: #000; text-decoration: none; font-weight: 500; }
    .admin-table a:hover { text-decoration: underline; }
    .user-cell { display: flex; align-items: center; gap: 0.75rem; }
    .user-avatar { width: 32px; height: 32px; border-radius: 50%; background: #e5e5e5; object-fit: cover; flex-shrink: 0; }
    .user-name { font-weight: 600; }
    .badge { display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-admin { background: #000; color: #fff; }
    .badge-editor { background: #d4d4d4; color: #333; }
    .badge-author { background: #e5e5e5; color: #555; }
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
</style>

<div class="admin-header">
    <h1><?= h($pageTitle) ?></h1>
    <a href="<?= url('admin/users/create') ?>" class="btn btn-primary">New User</a>
</div>

<?php if (!empty($users)): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Last Login</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td>
                        <div class="user-cell">
                            <?php if (!empty($u['avatar'])): ?>
                                <img src="<?= upload_url($u['avatar']) ?>" alt="" class="user-avatar">
                            <?php else: ?>
                                <div class="user-avatar" style="display:flex;align-items:center;justify-content:center;font-size:0.75rem;color:#999;font-weight:700;">
                                    <?= h(strtoupper(mb_substr($u['name'] ?? '?', 0, 1))) ?>
                                </div>
                            <?php endif; ?>
                            <span class="user-name"><?= h($u['name'] ?? '—') ?></span>
                        </div>
                    </td>
                    <td><?= h($u['email'] ?? '') ?></td>
                    <td>
                        <?php
                            $role = $u['role'] ?? 'author';
                            $roleBadge = match ($role) {
                                'super_admin' => 'badge-admin',
                                'editor'      => 'badge-editor',
                                default       => 'badge-author',
                            };
                        ?>
                        <span class="badge <?= $roleBadge ?>"><?= h(ucfirst($role)) ?></span>
                    </td>
                    <td>
                        <?php if (!empty($u['last_login_at'])): ?>
                            <?= h(date('M j, Y g:ia', strtotime($u['last_login_at']))) ?>
                        <?php else: ?>
                            <span style="color: #999;">Never</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="<?= url('admin/users/' . (int) $u['id'] . '/edit') ?>" class="btn btn-sm btn-outline">Edit</a>
                        <form method="POST" action="<?= url('admin/users/' . (int) $u['id'] . '/delete') ?>" onsubmit="return confirm('Delete this user?');">
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
                <a href="<?= url('admin/users?page=' . $paginator->prevPage()) ?>">Prev</a>
            <?php else: ?>
                <span class="disabled">Prev</span>
            <?php endif; ?>

            <?php foreach ($paginator->pages() as $page): ?>
                <?php if ($page === '...'): ?>
                    <span class="ellipsis">...</span>
                <?php elseif ($page === $paginator->currentPage): ?>
                    <span class="active"><?= $page ?></span>
                <?php else: ?>
                    <a href="<?= url('admin/users?page=' . $page) ?>"><?= $page ?></a>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($paginator->hasNext()): ?>
                <a href="<?= url('admin/users?page=' . $paginator->nextPage()) ?>">Next</a>
            <?php else: ?>
                <span class="disabled">Next</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div class="empty-state">
        <p>No users found.</p>
    </div>
<?php endif; ?>
