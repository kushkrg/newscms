<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #000; }
    .btn { display: inline-block; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; border: 1px solid #000; cursor: pointer; transition: all 0.15s ease; }
    .btn-primary { background: #000; color: #fff; }
    .btn-primary:hover { background: #222; }
    .btn-outline { background: #fff; color: #000; border: 1px solid #ccc; }
    .btn-outline:hover { border-color: #000; }
    .edit-form { max-width: 520px; }
    .form-group { display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 1.25rem; }
    .form-group label { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #333; }
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group select { padding: 0.55rem 0.75rem; border: 1px solid #ccc; font-size: 0.9rem; background: #fff; color: #000; width: 100%; box-sizing: border-box; font-family: inherit; }
    .form-group input:focus,
    .form-group select:focus { outline: none; border-color: #000; }
    .form-group .note { font-size: 0.75rem; color: #999; margin-top: 0.15rem; }
    .form-actions { display: flex; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e5e5; }
</style>

<div class="admin-header">
    <h1><?= h($pageTitle) ?></h1>
    <a href="<?= url('admin/subscribers') ?>" class="btn btn-outline">Back to List</a>
</div>

<form method="POST" action="<?= url('admin/subscribers/' . (int) $subscriber['id'] . '/update') ?>" class="edit-form">
    <?= \App\Core\Csrf::field() ?>

    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="<?= h($subscriber['email'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= h($subscriber['name'] ?? '') ?>" placeholder="Optional">
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="active" <?= ($subscriber['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="unsubscribed" <?= ($subscriber['status'] ?? '') === 'unsubscribed' ? 'selected' : '' ?>>Unsubscribed</option>
        </select>
    </div>

    <div class="form-group">
        <label>Subscribed At</label>
        <input type="text" value="<?= h($subscriber['subscribed_at'] ?? '—') ?>" disabled>
        <span class="note">This field is read-only</span>
    </div>

    <?php if (!empty($subscriber['unsubscribed_at'])): ?>
    <div class="form-group">
        <label>Unsubscribed At</label>
        <input type="text" value="<?= h($subscriber['unsubscribed_at']) ?>" disabled>
        <span class="note">This field is read-only</span>
    </div>
    <?php endif; ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update Subscriber</button>
        <a href="<?= url('admin/subscribers') ?>" class="btn btn-outline">Cancel</a>
    </div>
</form>
