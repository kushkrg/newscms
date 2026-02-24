<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #000; }
    .create-panel { padding: 1.25rem; background: #fafafa; border: 1px solid #e5e5e5; margin-bottom: 2rem; }
    .create-panel h2 { font-size: 1rem; font-weight: 700; margin: 0 0 1rem 0; color: #000; }
    .redirect-form { display: flex; gap: 0.75rem; align-items: flex-end; flex-wrap: wrap; }
    .redirect-form .form-group { display: flex; flex-direction: column; gap: 0.25rem; flex: 1; min-width: 160px; }
    .redirect-form .form-group label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #333; }
    .redirect-form .form-group input,
    .redirect-form .form-group select { padding: 0.5rem 0.75rem; border: 1px solid #ccc; font-size: 0.9rem; background: #fff; color: #000; }
    .redirect-form .form-group input:focus,
    .redirect-form .form-group select:focus { outline: none; border-color: #000; }
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
    .badge { display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-301 { background: #000; color: #fff; }
    .badge-302 { background: #e5e5e5; color: #333; }
    .actions { display: flex; align-items: center; gap: 0.5rem; }
    .actions form { margin: 0; display: inline; }
    .mono { font-family: monospace; font-size: 0.85rem; }
    .empty-state { text-align: center; padding: 3rem 1rem; color: #999; font-size: 0.95rem; }
</style>

<div class="admin-header">
    <h1><?= h($pageTitle) ?></h1>
</div>

<div class="create-panel">
    <h2>Create Redirect</h2>
    <form method="POST" action="<?= url('admin/redirects/store') ?>" class="redirect-form">
        <?= \App\Core\Csrf::field() ?>
        <div class="form-group">
            <label for="from_path">From Path</label>
            <input type="text" name="from_path" id="from_path" required placeholder="/old-page">
        </div>
        <div class="form-group">
            <label for="to_url">To URL</label>
            <input type="text" name="to_url" id="to_url" required placeholder="/new-page or https://...">
        </div>
        <div class="form-group" style="flex: 0 0 auto; min-width: 100px;">
            <label for="type">Type</label>
            <select name="type" id="type">
                <option value="301">301 (Permanent)</option>
                <option value="302">302 (Temporary)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>

<?php if (!empty($redirects)): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>From Path</th>
                <th>To URL</th>
                <th>Type</th>
                <th>Hits</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($redirects as $redir): ?>
                <tr>
                    <td class="mono"><?= h($redir['from_path'] ?? '') ?></td>
                    <td class="mono"><?= h($redir['to_url'] ?? '') ?></td>
                    <td>
                        <?php $code = (int) ($redir['type'] ?? 301); ?>
                        <span class="badge <?= $code === 301 ? 'badge-301' : 'badge-302' ?>"><?= $code ?></span>
                    </td>
                    <td><?= (int) ($redir['hit_count'] ?? 0) ?></td>
                    <td class="actions">
                        <form method="POST" action="<?= url('admin/redirects/' . (int) $redir['id'] . '/delete') ?>" onsubmit="return confirm('Delete this redirect?');">
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
        <p>No redirects configured.</p>
    </div>
<?php endif; ?>
