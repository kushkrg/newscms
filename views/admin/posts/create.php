<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #000; }
    .form-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
    .form-main, .form-sidebar { display: flex; flex-direction: column; gap: 1.25rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.35rem; }
    .form-group label { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #333; }
    .form-group input[type="text"],
    .form-group input[type="url"],
    .form-group input[type="file"],
    .form-group select,
    .form-group textarea { padding: 0.55rem 0.75rem; border: 1px solid #ccc; font-size: 0.9rem; background: #fff; color: #000; width: 100%; box-sizing: border-box; font-family: inherit; }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus { outline: none; border-color: #000; }
    .form-group .note { font-size: 0.75rem; color: #999; }
    .form-group textarea.small { height: 80px; resize: vertical; }
    .form-group textarea.large { height: 400px; resize: vertical; }
    .form-section { border: 1px solid #e5e5e5; padding: 1.25rem; margin-top: 0.5rem; }
    .form-section-title { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 1rem; color: #000; cursor: pointer; user-select: none; }
    .form-section-title::before { content: '+ '; }
    .form-section-title.open::before { content: '- '; }
    .form-section-body { display: none; flex-direction: column; gap: 1rem; }
    .form-section-body.open { display: flex; }
    .checkbox-group { display: flex; align-items: center; gap: 0.5rem; }
    .checkbox-group input[type="checkbox"] { width: 16px; height: 16px; accent-color: #000; }
    .checkbox-group label { font-size: 0.85rem; font-weight: 400; text-transform: none; letter-spacing: 0; color: #000; }
    .file-attach { border: 1px solid #e5e5e5; padding: 1rem; margin-top: 0.25rem; }
    .file-attach__title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #000; margin-bottom: 0.75rem; }
    .file-attach__tabs { display: flex; gap: 0; margin-bottom: 0.75rem; }
    .file-attach__tab { padding: 0.4rem 0.75rem; font-size: 0.78rem; font-weight: 600; border: 1px solid #ccc; background: #f5f5f5; color: #666; cursor: pointer; transition: all 0.15s; }
    .file-attach__tab:first-child { border-right: 0; }
    .file-attach__tab.active { background: #000; color: #fff; border-color: #000; }
    .file-attach__panel { display: none; }
    .file-attach__panel.active { display: block; }
    .file-attach__current { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.6rem; background: #f5f5f5; border: 1px solid #e5e5e5; font-size: 0.82rem; margin-bottom: 0.5rem; }
    .file-attach__current svg { flex-shrink: 0; }
    .file-attach__current span { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .file-attach__remove { background: none; border: none; cursor: pointer; color: #999; font-size: 1rem; padding: 0 0.25rem; }
    .file-attach__remove:hover { color: #000; }
    .media-picker-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 0.5rem; max-height: 200px; overflow-y: auto; padding: 0.5rem; border: 1px solid #e5e5e5; background: #fafafa; }
    .media-picker-item { cursor: pointer; border: 2px solid transparent; padding: 0.35rem; text-align: center; transition: border-color 0.15s; background: #fff; }
    .media-picker-item:hover { border-color: #666; }
    .media-picker-item.selected { border-color: #000; background: #f0f0f0; }
    .media-picker-item img { width: 100%; height: 50px; object-fit: cover; }
    .media-picker-item .file-icon { width: 100%; height: 50px; display: flex; align-items: center; justify-content: center; background: #f5f5f5; }
    .media-picker-item .name { font-size: 0.65rem; color: #666; margin-top: 0.2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .btn { display: inline-block; padding: 0.6rem 1.5rem; font-size: 0.9rem; font-weight: 600; text-decoration: none; border: 1px solid #000; cursor: pointer; transition: all 0.15s ease; }
    .btn-primary { background: #000; color: #fff; }
    .btn-primary:hover { background: #222; }
    .btn-outline { background: #fff; color: #000; }
    .btn-outline:hover { background: #f5f5f5; }
    .form-actions { display: flex; gap: 0.75rem; margin-top: 1rem; }
    @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
</style>

<div class="admin-header">
    <h1><?= h($pageTitle) ?></h1>
    <a href="<?= url('admin/posts') ?>" class="btn btn-outline">Back to Posts</a>
</div>

<form method="POST" action="<?= url('admin/posts/store') ?>" enctype="multipart/form-data">
    <?= \App\Core\Csrf::field() ?>

    <div class="form-grid">
        <div class="form-main">
            <div class="form-group">
                <label for="post-title">Title</label>
                <input type="text" name="title" id="post-title" required placeholder="Enter post title">
            </div>

            <div class="form-group">
                <label for="post-slug">Slug</label>
                <input type="text" name="slug" id="post-slug" placeholder="auto-generated-from-title">
                <span class="note">Auto-generated from title if left empty.</span>
            </div>

            <div class="form-group">
                <label for="editor">Content</label>
                <textarea name="content" id="editor" class="large"></textarea>
            </div>

            <div class="form-group">
                <label for="excerpt">Excerpt</label>
                <textarea name="excerpt" id="excerpt" class="small" placeholder="Brief summary of the post..."></textarea>
            </div>

            <div class="form-section">
                <div class="form-section-title" onclick="this.classList.toggle('open'); this.nextElementSibling.classList.toggle('open');">
                    SEO Settings
                </div>
                <div class="form-section-body">
                    <div class="form-group">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" placeholder="SEO title (defaults to post title)">
                    </div>
                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" class="small" placeholder="SEO description (150-160 characters recommended)"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="canonical_url">Canonical URL</label>
                        <input type="url" name="canonical_url" id="canonical_url" placeholder="https://example.com/original-article">
                    </div>
                    <div class="form-group">
                        <label for="schema_type">Schema Type</label>
                        <select name="schema_type" id="schema_type">
                            <option value="Article">Article</option>
                            <option value="NewsArticle">NewsArticle</option>
                            <option value="BlogPosting">BlogPosting</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-sidebar">
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="scheduled">Scheduled</option>
                </select>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id">
                    <option value="">— Select Category —</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int) $cat['id'] ?>"><?= h($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="tags">Tags</label>
                <input type="text" name="tags" id="tags" placeholder="tag1, tag2, tag3">
                <span class="note">Comma-separated tag names.</span>
            </div>

            <div class="form-group">
                <label for="featured_image">Featured Image</label>
                <input type="file" name="featured_image" id="featured_image" accept="image/*">
            </div>

            <div class="form-group">
                <label for="featured_image_alt">Image Alt Text</label>
                <input type="text" name="featured_image_alt" id="featured_image_alt" placeholder="Describe the image">
            </div>

            <!-- Downloadable File -->
            <div class="file-attach">
                <div class="file-attach__title">Downloadable File</div>
                <div class="file-attach__tabs">
                    <div class="file-attach__tab active" data-panel="upload-panel">Upload File</div>
                    <div class="file-attach__tab" data-panel="media-panel">Choose from Media</div>
                </div>
                <div id="upload-panel" class="file-attach__panel active">
                    <div class="form-group" style="margin-bottom:0">
                        <input type="file" name="download_file" id="download_file" accept=".pdf,.zip,.rar,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        <span class="note">Allowed: PDF, ZIP, RAR, DOC, XLS, PPT (max 50MB)</span>
                    </div>
                </div>
                <div id="media-panel" class="file-attach__panel">
                    <div class="media-picker-grid" id="mediaPicker">Loading...</div>
                    <input type="hidden" name="download_file_media" id="download_file_media" value="">
                </div>
            </div>

            <div class="form-group" style="gap: 0.6rem;">
                <label style="margin-bottom: 0.25rem;">Options</label>
                <div class="checkbox-group">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1">
                    <label for="is_featured">Featured post</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="is_sticky" id="is_sticky" value="1">
                    <label for="is_sticky">Sticky post</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="allow_comments" id="allow_comments" value="1" checked>
                    <label for="allow_comments">Allow comments</label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Publish Post</button>
            </div>
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

    // File attach tabs
    document.querySelectorAll('.file-attach__tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.file-attach__tab').forEach(function(t) { t.classList.remove('active'); });
            document.querySelectorAll('.file-attach__panel').forEach(function(p) { p.classList.remove('active'); });
            tab.classList.add('active');
            document.getElementById(tab.dataset.panel).classList.add('active');
        });
    });

    // Load media for picker
    (function() {
        var picker = document.getElementById('mediaPicker');
        if (!picker) return;
        fetch('<?= url('admin/media/json') ?>')
            .then(function(r) { return r.json(); })
            .then(function(items) {
                if (!items.length) { picker.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:#999;font-size:0.82rem;padding:1rem;">No media files found</div>'; return; }
                picker.innerHTML = '';
                items.forEach(function(item) {
                    var div = document.createElement('div');
                    div.className = 'media-picker-item';
                    div.dataset.path = item.path;
                    div.dataset.name = item.name;
                    var isImage = /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(item.name);
                    if (isImage && item.thumb) {
                        div.innerHTML = '<img src="' + item.thumb + '" alt=""><div class="name">' + item.name + '</div>';
                    } else {
                        var ext = item.name.split('.').pop().toUpperCase();
                        div.innerHTML = '<div class="file-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div><div class="name">' + item.name + '</div>';
                    }
                    div.addEventListener('click', function() {
                        document.querySelectorAll('.media-picker-item').forEach(function(el) { el.classList.remove('selected'); });
                        div.classList.add('selected');
                        document.getElementById('download_file_media').value = item.path;
                    });
                    picker.appendChild(div);
                });
            })
            .catch(function() { picker.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:#999;font-size:0.82rem;padding:1rem;">Failed to load media</div>'; });
    })();
</script>
