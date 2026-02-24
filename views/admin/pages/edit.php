<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #000; }
    .form-stack { display: flex; flex-direction: column; gap: 1.25rem; max-width: 800px; }
    .form-group { display: flex; flex-direction: column; gap: 0.35rem; }
    .form-group label { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #333; }
    .form-group input[type="text"],
    .form-group select,
    .form-group textarea { padding: 0.55rem 0.75rem; border: 1px solid #ccc; font-size: 0.9rem; background: #fff; color: #000; width: 100%; box-sizing: border-box; font-family: inherit; }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus { outline: none; border-color: #000; }
    .form-group .note { font-size: 0.75rem; color: #999; }
    .form-group textarea.small { height: 80px; resize: vertical; }
    .form-group textarea.large { height: 400px; resize: vertical; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-section { border: 1px solid #e5e5e5; padding: 1.25rem; margin-top: 0.5rem; }
    .form-section-title { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 1rem; color: #000; cursor: pointer; user-select: none; }
    .form-section-title::before { content: '+ '; }
    .form-section-title.open::before { content: '- '; }
    .form-section-body { display: none; flex-direction: column; gap: 1rem; }
    .form-section-body.open { display: flex; }
    .btn { display: inline-block; padding: 0.6rem 1.5rem; font-size: 0.9rem; font-weight: 600; text-decoration: none; border: 1px solid #000; cursor: pointer; transition: all 0.15s ease; }
    .btn-primary { background: #000; color: #fff; }
    .btn-primary:hover { background: #222; }
    .btn-outline { background: #fff; color: #000; }
    .btn-outline:hover { background: #f5f5f5; }
    .form-actions { display: flex; gap: 0.75rem; margin-top: 1rem; }
    @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }
</style>

<div class="admin-header">
    <h1><?= h($pageTitle) ?></h1>
    <a href="<?= url('admin/pages') ?>" class="btn btn-outline">Back to Pages</a>
</div>

<form method="POST" action="<?= url('admin/pages/' . (int) $page['id'] . '/update') ?>">
    <?= \App\Core\Csrf::field() ?>

    <div class="form-stack">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" required value="<?= h($page['title'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" name="slug" id="slug" value="<?= h($page['slug'] ?? '') ?>">
            <span class="note">Auto-generated from title if left empty.</span>
        </div>

        <div class="form-group">
            <label for="editor">Content</label>
            <textarea name="content" id="editor" class="large"><?= h($page['content'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="template">Template</label>
                <select name="template" id="template">
                    <option value="default" <?= ($page['template'] ?? '') === 'default' ? 'selected' : '' ?>>Default</option>
                    <option value="full_width" <?= ($page['template'] ?? '') === 'full_width' ? 'selected' : '' ?>>Full Width</option>
                    <option value="sidebar" <?= ($page['template'] ?? '') === 'sidebar' ? 'selected' : '' ?>>Sidebar</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="draft" <?= ($page['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= ($page['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title" onclick="this.classList.toggle('open'); this.nextElementSibling.classList.toggle('open');">
                SEO Settings
            </div>
            <div class="form-section-body">
                <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" value="<?= h($page['meta_title'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" class="small"><?= h($page['meta_description'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Page</button>
        </div>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#editor',
        base_url: 'https://cdn.jsdelivr.net/npm/tinymce@6',
        suffix: '.min',
        height: 400,
        menubar: false,
        plugins: 'lists link image code table wordcount fullscreen media',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright | bullist numlist | link image media | table | code fullscreen',
        skin: 'oxide',
        content_css: 'default',
        branding: false,
        promotion: false
    });
</script>
