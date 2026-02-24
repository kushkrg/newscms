<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #000; }
    .cat-layout { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; }
    .cat-form-panel { background: #fafafa; border: 1px solid #e5e5e5; padding: 1.5rem; }
    .cat-form-panel h2 { font-size: 1rem; font-weight: 700; margin: 0 0 1.25rem 0; color: #000; }
    .form-group { display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 1rem; }
    .form-group label { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #333; }
    .form-group input[type="text"],
    .form-group select,
    .form-group textarea { padding: 0.55rem 0.75rem; border: 1px solid #ccc; font-size: 0.9rem; background: #fff; color: #000; width: 100%; box-sizing: border-box; font-family: inherit; }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus { outline: none; border-color: #000; }
    .form-group textarea { height: 70px; resize: vertical; }
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
    .actions { display: flex; align-items: center; gap: 0.5rem; }
    .actions form { margin: 0; display: inline; }
    .child-indent { padding-left: 1.5rem; color: #666; }
    .child-indent::before { content: '— '; color: #ccc; }
    .form-actions { display: flex; gap: 0.75rem; }
    .empty-state { text-align: center; padding: 3rem 1rem; color: #999; font-size: 0.95rem; }
    @media (max-width: 768px) { .cat-layout { grid-template-columns: 1fr; } }
</style>

<div class="admin-header">
    <h1><?= h($pageTitle) ?></h1>
</div>

<div class="cat-layout">
    <div class="cat-form-panel">
        <?php if ($editCategory): ?>
            <h2>Edit Category</h2>
            <form method="POST" action="<?= url('admin/categories/' . (int) $editCategory['id'] . '/update') ?>">
                <?= \App\Core\Csrf::field() ?>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" required value="<?= h($editCategory['name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" value="<?= h($editCategory['slug'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="parent_id">Parent Category</label>
                    <select name="parent_id" id="parent_id">
                        <option value="">— None (Top Level) —</option>
                        <?php foreach ($categories as $cat): ?>
                            <?php if ((int) $cat['id'] !== (int) $editCategory['id']): ?>
                                <option value="<?= (int) $cat['id'] ?>" <?= ((int)($editCategory['parent_id'] ?? 0)) === (int) $cat['id'] ? 'selected' : '' ?>>
                                    <?= h($cat['name']) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description"><?= h($editCategory['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="<?= h($editCategory['meta_title'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea name="meta_description" id="meta_description"><?= h($editCategory['meta_description'] ?? '') ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Category</button>
                    <a href="<?= url('admin/categories') ?>" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        <?php else: ?>
            <h2>Add New Category</h2>
            <form method="POST" action="<?= url('admin/categories/store') ?>">
                <?= \App\Core\Csrf::field() ?>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" required placeholder="Category name">
                </div>

                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" placeholder="auto-generated">
                </div>

                <div class="form-group">
                    <label for="parent_id">Parent Category</label>
                    <select name="parent_id" id="parent_id">
                        <option value="">— None (Top Level) —</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int) $cat['id'] ?>"><?= h($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Optional category description"></textarea>
                </div>

                <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" placeholder="SEO title">
                </div>

                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" placeholder="SEO description"></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <div>
        <?php if (!empty($categories)): ?>
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
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td>
                                <?php if (!empty($cat['parent_id'])): ?>
                                    <span class="child-indent"><?= h($cat['name']) ?></span>
                                <?php else: ?>
                                    <strong><?= h($cat['name']) ?></strong>
                                <?php endif; ?>
                            </td>
                            <td><?= h($cat['slug']) ?></td>
                            <td><?= (int) ($cat['post_count'] ?? 0) ?></td>
                            <td class="actions">
                                <a href="<?= url('admin/categories/' . (int) $cat['id'] . '/edit') ?>" class="btn btn-sm btn-outline">Edit</a>
                                <form method="POST" action="<?= url('admin/categories/' . (int) $cat['id'] . '/delete') ?>" onsubmit="return confirm('Delete this category?');">
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
                <p>No categories yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
