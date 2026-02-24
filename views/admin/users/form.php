<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #000; }
    .form-stack { display: flex; flex-direction: column; gap: 1.25rem; max-width: 640px; }
    .form-group { display: flex; flex-direction: column; gap: 0.35rem; }
    .form-group label { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #333; }
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"],
    .form-group input[type="url"],
    .form-group input[type="file"],
    .form-group select,
    .form-group textarea { padding: 0.55rem 0.75rem; border: 1px solid #ccc; font-size: 0.9rem; background: #fff; color: #000; width: 100%; box-sizing: border-box; font-family: inherit; }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus { outline: none; border-color: #000; }
    .form-group .note { font-size: 0.75rem; color: #999; }
    .form-group textarea { height: 100px; resize: vertical; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .btn { display: inline-block; padding: 0.6rem 1.5rem; font-size: 0.9rem; font-weight: 600; text-decoration: none; border: 1px solid #000; cursor: pointer; transition: all 0.15s ease; }
    .btn-primary { background: #000; color: #fff; }
    .btn-primary:hover { background: #222; }
    .btn-outline { background: #fff; color: #000; }
    .btn-outline:hover { background: #f5f5f5; }
    .form-actions { display: flex; gap: 0.75rem; margin-top: 1rem; }
    .current-avatar { margin-top: 0.5rem; }
    .current-avatar img { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 1px solid #e5e5e5; }
    .current-avatar-label { font-size: 0.75rem; color: #666; margin-top: 0.25rem; }
    @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }
</style>

<?php
    $isEdit = !empty($user);
    $formAction = $isEdit
        ? url('admin/users/' . (int) $user['id'] . '/update')
        : url('admin/users/store');
    $heading = $isEdit ? 'Edit User' : 'Create User';
?>

<div class="admin-header">
    <h1><?= h($heading) ?></h1>
    <a href="<?= url('admin/users') ?>" class="btn btn-outline">Back to Users</a>
</div>

<form method="POST" action="<?= $formAction ?>" enctype="multipart/form-data">
    <?= \App\Core\Csrf::field() ?>

    <div class="form-stack">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required value="<?= h($user['name'] ?? '') ?>" placeholder="Full name">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required value="<?= h($user['email'] ?? '') ?>" placeholder="user@example.com">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" <?= $isEdit ? '' : 'required' ?> placeholder="<?= $isEdit ? 'Leave blank to keep current' : 'Enter password' ?>">
            <?php if ($isEdit): ?>
                <span class="note">Leave blank to keep the current password.</span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role">
                <option value="contributor" <?= ($user['role'] ?? '') === 'contributor' ? 'selected' : '' ?>>Contributor</option>
                <option value="author" <?= ($user['role'] ?? '') === 'author' ? 'selected' : '' ?>>Author</option>
                <option value="editor" <?= ($user['role'] ?? '') === 'editor' ? 'selected' : '' ?>>Editor</option>
                <option value="super_admin" <?= ($user['role'] ?? '') === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea name="bio" id="bio" placeholder="Short biography"><?= h($user['bio'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="website">Website</label>
                <input type="url" name="website" id="website" value="<?= h($user['website'] ?? '') ?>" placeholder="https://example.com">
            </div>
            <div class="form-group">
                <label for="twitter">Twitter</label>
                <input type="text" name="twitter" id="twitter" value="<?= h($user['twitter'] ?? '') ?>" placeholder="@handle">
            </div>
        </div>

        <div class="form-group">
            <label for="linkedin">LinkedIn</label>
            <input type="url" name="linkedin" id="linkedin" value="<?= h($user['linkedin'] ?? '') ?>" placeholder="https://linkedin.com/in/username">
        </div>

        <div class="form-group">
            <label for="avatar">Avatar</label>
            <?php if ($isEdit && !empty($user['avatar'])): ?>
                <div class="current-avatar">
                    <img src="<?= upload_url($user['avatar']) ?>" alt="Current avatar">
                    <div class="current-avatar-label">Current avatar</div>
                </div>
            <?php endif; ?>
            <input type="file" name="avatar" id="avatar" accept="image/*">
            <?php if ($isEdit): ?>
                <span class="note">Leave empty to keep the current avatar.</span>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update User' : 'Create User' ?></button>
            <a href="<?= url('admin/users') ?>" class="btn btn-outline">Cancel</a>
        </div>
    </div>
</form>
