<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #000; }
    .quick-add { display: flex; gap: 0.5rem; align-items: flex-end; margin-bottom: 1.5rem; padding: 1rem; background: #fafafa; border: 1px solid #e5e5e5; }
    .quick-add .form-group { display: flex; flex-direction: column; gap: 0.25rem; flex: 1; }
    .quick-add .form-group label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #333; }
    .quick-add .form-group input { padding: 0.5rem 0.75rem; border: 1px solid #ccc; font-size: 0.9rem; background: #fff; color: #000; }
    .quick-add .form-group input:focus { outline: none; border-color: #000; }
    .btn { display: inline-block; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; border: 1px solid #000; cursor: pointer; transition: all 0.15s ease; }
    .btn-primary { background: #000; color: #fff; }
    .btn-primary:hover { background: #222; }
    .btn-sm { padding: 0.3rem 0.6rem; font-size: 0.8rem; }
    .btn-danger { background: #fff; color: #000; border: 1px solid #999; }
    .btn-danger:hover { background: #000; color: #fff; }
    .admin-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .admin-table th { text-align: left; padding: 0.75rem 1rem; border-bottom: 2px solid #000; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #333; }
    .admin-table td { padding: 0.75rem 1rem; border-bottom: 1px solid #e5e5e5; vertical-align: middle; }
    .admin-table tr:hover { background: #fafafa; }
    .actions { display: flex; align-items: center; gap: 0.5rem; }
    .actions form { margin: 0; display: inline; }
    .empty-state { text-align: center; padding: 3rem 1rem; color: #999; font-size: 0.95rem; }
</style>

<div class="admin-header">
    <h1><?= h($pageTitle) ?></h1>
</div>

<form method="POST" action="<?= url('admin/tags/store') ?>" class="quick-add">
    <?= \App\Core\Csrf::field() ?>
    <div class="form-group">
        <label for="tag-name">Tag Name</label>
        <input type="text" name="name" id="tag-name" required placeholder="Enter tag name">
    </div>
    <button type="submit" class="btn btn-primary">Add Tag</button>
</form>

<?php if (!empty($tags)): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Posts</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tags as $tag): ?>
                <tr>
                    <td><strong><?= h($tag['name']) ?></strong></td>
                    <td><?= h($tag['slug']) ?></td>
                    <td><?= (int) ($tag['post_count'] ?? 0) ?></td>
                    <td class="actions">
                        <form method="POST" action="<?= url('admin/tags/' . (int) $tag['id'] . '/delete') ?>" onsubmit="return confirm('Delete this tag?');">
                            <?= \App\Core\Csrf::field() ?>
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="empty-state">
        <p>No tags yet.</p>
    </div>
<?php endif; ?>
