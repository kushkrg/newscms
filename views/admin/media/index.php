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
    .upload-panel { padding: 1.25rem; background: #fafafa; border: 1px solid #e5e5e5; margin-bottom: 2rem; }
    .upload-panel h2 { font-size: 1rem; font-weight: 700; margin: 0 0 1rem 0; color: #000; }
    .upload-form { display: flex; gap: 0.75rem; align-items: flex-end; flex-wrap: wrap; }
    .upload-form .form-group { display: flex; flex-direction: column; gap: 0.25rem; }
    .upload-form .form-group label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #333; }
    .upload-form .form-group input[type="file"],
    .upload-form .form-group input[type="text"] { padding: 0.5rem 0.75rem; border: 1px solid #ccc; font-size: 0.9rem; background: #fff; color: #000; }
    .upload-form .form-group input:focus { outline: none; border-color: #000; }
    .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.25rem; }
    .media-card { border: 1px solid #e5e5e5; background: #fff; overflow: hidden; transition: border-color 0.15s ease; }
    .media-card:hover { border-color: #000; }
    .media-thumb { width: 100%; height: 160px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; overflow: hidden; cursor: pointer; position: relative; }
    .media-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .media-thumb .copy-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.7); color: #fff; display: none; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 600; }
    .media-card:hover .copy-overlay { display: flex; }
    .media-info { padding: 0.75rem; }
    .media-filename { font-size: 0.8rem; font-weight: 600; color: #000; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.25rem; }
    .media-meta { font-size: 0.72rem; color: #999; line-height: 1.4; }
    .media-actions { padding: 0 0.75rem 0.75rem; }
    .media-actions form { margin: 0; }
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
</div>

<div class="upload-panel">
    <h2>Upload Media</h2>
    <form method="POST" action="<?= url('admin/media/upload') ?>" enctype="multipart/form-data" class="upload-form">
        <?= \App\Core\Csrf::field() ?>
        <div class="form-group">
            <label for="media-file">File</label>
            <input type="file" name="file" id="media-file" required accept="image/*">
        </div>
        <div class="form-group">
            <label for="alt_text">Alt Text</label>
            <input type="text" name="alt_text" id="alt_text" placeholder="Describe the image">
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>

<?php if (!empty($media)): ?>
    <div class="media-grid">
        <?php foreach ($media as $item): ?>
            <div class="media-card">
                <div class="media-thumb" onclick="copyToClipboard('<?= h(upload_url($item['path_original'] ?? $item['path'] ?? '')) ?>')" title="Click to copy URL">
                    <?php
                        $thumb = $item['path_thumb'] ?? $item['path_original'] ?? $item['path'] ?? '';
                    ?>
                    <?php if ($thumb): ?>
                        <img src="<?= upload_url($thumb) ?>" alt="<?= h($item['alt_text'] ?? $item['filename'] ?? '') ?>">
                    <?php else: ?>
                        <span style="color: #ccc; font-size: 0.8rem;">No preview</span>
                    <?php endif; ?>
                    <div class="copy-overlay">Click to copy URL</div>
                </div>
                <div class="media-info">
                    <div class="media-filename"><?= h($item['filename'] ?? $item['original_name'] ?? '—') ?></div>
                    <div class="media-meta">
                        <?php if (!empty($item['width']) && !empty($item['height'])): ?>
                            <?= (int) $item['width'] ?> x <?= (int) $item['height'] ?>px<br>
                        <?php endif; ?>
                        <?php if (!empty($item['file_size'])): ?>
                            <?= h(number_format((int) $item['file_size'] / 1024, 1)) ?> KB
                        <?php endif; ?>
                    </div>
                </div>
                <div class="media-actions">
                    <form method="POST" action="<?= url('admin/media/' . (int) $item['id'] . '/delete') ?>" onsubmit="return confirm('Delete this file?');">
                        <?= \App\Core\Csrf::field() ?>
                        <button type="submit" class="btn btn-sm btn-danger" style="width: 100%;">Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($paginator->hasPages()): ?>
        <div class="pagination">
            <?php if ($paginator->hasPrev()): ?>
                <a href="<?= url('admin/media?page=' . $paginator->prevPage()) ?>">Prev</a>
            <?php else: ?>
                <span class="disabled">Prev</span>
            <?php endif; ?>

            <?php foreach ($paginator->pages() as $page): ?>
                <?php if ($page === '...'): ?>
                    <span class="ellipsis">...</span>
                <?php elseif ($page === $paginator->currentPage): ?>
                    <span class="active"><?= $page ?></span>
                <?php else: ?>
                    <a href="<?= url('admin/media?page=' . $page) ?>"><?= $page ?></a>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($paginator->hasNext()): ?>
                <a href="<?= url('admin/media?page=' . $paginator->nextPage()) ?>">Next</a>
            <?php else: ?>
                <span class="disabled">Next</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div class="empty-state">
        <p>No media uploaded yet.</p>
    </div>
<?php endif; ?>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        var el = document.createElement('div');
        el.textContent = 'URL copied to clipboard';
        el.style.cssText = 'position:fixed;top:1rem;right:1rem;background:#000;color:#fff;padding:0.5rem 1rem;font-size:0.85rem;z-index:9999;';
        document.body.appendChild(el);
        setTimeout(function() { el.remove(); }, 2000);
    });
}
</script>
