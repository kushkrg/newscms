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
    .filter-bar { display: flex; gap: 0.75rem; align-items: center; margin-bottom: 1.5rem; padding: 1rem; background: #fafafa; border: 1px solid #e5e5e5; flex-wrap: wrap; }
    .filter-bar select,
    .filter-bar input[type="text"] { padding: 0.45rem 0.6rem; border: 1px solid #ccc; font-size: 0.875rem; background: #fff; color: #000; min-width: 140px; }
    .filter-bar select:focus,
    .filter-bar input[type="text"]:focus { outline: none; border-color: #000; }
    .admin-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .admin-table th { text-align: left; padding: 0.75rem 1rem; border-bottom: 2px solid #000; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #333; }
    .admin-table td { padding: 0.75rem 1rem; border-bottom: 1px solid #e5e5e5; vertical-align: middle; }
    .admin-table tr:hover { background: #fafafa; }
    .admin-table a { color: #000; text-decoration: none; font-weight: 500; }
    .admin-table a:hover { text-decoration: underline; }
    .badge { display: inline-block; padding: 0.2rem 0.5rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-draft { background: #e5e5e5; color: #555; }
    .badge-published { background: #000; color: #fff; }
    .badge-archived { background: #f0f0f0; color: #999; }
    .badge-scheduled { background: #d4d4d4; color: #333; }
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
    <a href="<?= url('admin/posts/create') ?>" class="btn btn-primary">New Post</a>
</div>

<form method="GET" action="<?= url('admin/posts') ?>" class="filter-bar">
    <select name="status">
        <option value="">All Statuses</option>
        <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
        <option value="published" <?= ($filters['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
        <option value="archived" <?= ($filters['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archived</option>
    </select>

    <select name="category_id">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= (int) $cat['id'] ?>" <?= ((string)($filters['category_id'] ?? '')) === ((string)$cat['id']) ? 'selected' : '' ?>>
                <?= h($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="search" placeholder="Search posts..." value="<?= h($filters['search'] ?? '') ?>">

    <button type="submit" class="btn btn-outline">Filter</button>
</form>

<?php if (!empty($posts)): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Author</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td>
                        <a href="<?= url('admin/posts/' . (int) $post['id'] . '/edit') ?>">
                            <?= h($post['title']) ?>
                        </a>
                    </td>
                    <td><?= h($post['category_name'] ?? '—') ?></td>
                    <td>
                        <?php
                            $status = $post['status'] ?? 'draft';
                            $badgeClass = match ($status) {
                                'published' => 'badge-published',
                                'archived'  => 'badge-archived',
                                'scheduled' => 'badge-scheduled',
                                default     => 'badge-draft',
                            };
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= h(ucfirst($status)) ?></span>
                    </td>
                    <td><?= h($post['author_name'] ?? '—') ?></td>
                    <td><?= h(date('M j, Y', strtotime($post['created_at']))) ?></td>
                    <td class="actions">
                        <a href="<?= url('admin/posts/' . (int) $post['id'] . '/edit') ?>" class="btn btn-sm btn-outline">Edit</a>
                        <form method="POST" action="<?= url('admin/posts/' . (int) $post['id'] . '/delete') ?>" onsubmit="return confirm('Delete this post?');">
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
                <a href="<?= url('admin/posts?' . http_build_query(array_merge($filters, ['page' => $paginator->prevPage()]))) ?>">Prev</a>
            <?php else: ?>
                <span class="disabled">Prev</span>
            <?php endif; ?>

            <?php foreach ($paginator->pages() as $page): ?>
                <?php if ($page === '...'): ?>
                    <span class="ellipsis">...</span>
                <?php elseif ($page === $paginator->currentPage): ?>
                    <span class="active"><?= $page ?></span>
                <?php else: ?>
                    <a href="<?= url('admin/posts?' . http_build_query(array_merge($filters, ['page' => $page]))) ?>"><?= $page ?></a>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($paginator->hasNext()): ?>
                <a href="<?= url('admin/posts?' . http_build_query(array_merge($filters, ['page' => $paginator->nextPage()]))) ?>">Next</a>
            <?php else: ?>
                <span class="disabled">Next</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div class="empty-state">
        <p>No posts found.</p>
    </div>
<?php endif; ?>
