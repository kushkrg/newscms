<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #000; }
    .status-tabs { display: flex; gap: 0; margin-bottom: 1.5rem; border-bottom: 2px solid #e5e5e5; }
    .status-tabs a { padding: 0.6rem 1.25rem; font-size: 0.85rem; font-weight: 500; text-decoration: none; color: #666; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all 0.15s ease; }
    .status-tabs a:hover { color: #000; }
    .status-tabs a.active { color: #000; font-weight: 700; border-bottom-color: #000; }
    .btn { display: inline-block; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; border: 1px solid #000; cursor: pointer; transition: all 0.15s ease; }
    .btn-sm { padding: 0.3rem 0.6rem; font-size: 0.8rem; }
    .btn-approve { background: #000; color: #fff; border: 1px solid #000; }
    .btn-approve:hover { background: #222; }
    .btn-spam { background: #fff; color: #000; border: 1px solid #999; }
    .btn-spam:hover { background: #e5e5e5; }
    .btn-danger { background: #fff; color: #000; border: 1px solid #999; }
    .btn-danger:hover { background: #000; color: #fff; }
    .admin-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .admin-table th { text-align: left; padding: 0.75rem 1rem; border-bottom: 2px solid #000; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #333; }
    .admin-table td { padding: 0.75rem 1rem; border-bottom: 1px solid #e5e5e5; vertical-align: top; }
    .admin-table tr:hover { background: #fafafa; }
    .admin-table a { color: #000; text-decoration: none; font-weight: 500; }
    .admin-table a:hover { text-decoration: underline; }
    .comment-excerpt { color: #555; line-height: 1.4; max-width: 300px; }
    .comment-author { font-weight: 600; }
    .comment-email { font-size: 0.8rem; color: #999; }
    .actions { display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; }
    .actions form { margin: 0; display: inline; }
    .badge { display: inline-block; padding: 0.15rem 0.4rem; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
    .badge-pending { background: #e5e5e5; color: #555; }
    .badge-approved { background: #000; color: #fff; }
    .badge-spam { background: #d4d4d4; color: #666; }
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

<div class="status-tabs">
    <a href="<?= url('admin/comments') ?>" class="<?= empty($status) ? 'active' : '' ?>">All</a>
    <a href="<?= url('admin/comments?status=pending') ?>" class="<?= ($status ?? '') === 'pending' ? 'active' : '' ?>">Pending</a>
    <a href="<?= url('admin/comments?status=approved') ?>" class="<?= ($status ?? '') === 'approved' ? 'active' : '' ?>">Approved</a>
    <a href="<?= url('admin/comments?status=spam') ?>" class="<?= ($status ?? '') === 'spam' ? 'active' : '' ?>">Spam</a>
</div>

<?php if (!empty($comments)): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Author</th>
                <th>Comment</th>
                <th>Post</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comments as $comment): ?>
                <tr>
                    <td>
                        <div class="comment-author"><?= h($comment['author_name'] ?? 'Anonymous') ?></div>
                        <div class="comment-email"><?= h($comment['author_email'] ?? '') ?></div>
                    </td>
                    <td>
                        <div class="comment-excerpt">
                            <?= h(mb_strimwidth($comment['content'] ?? '', 0, 120, '...')) ?>
                        </div>
                        <?php
                            $cStatus = $comment['status'] ?? 'pending';
                            $badgeClass = match ($cStatus) {
                                'approved' => 'badge-approved',
                                'spam'     => 'badge-spam',
                                default    => 'badge-pending',
                            };
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= h(ucfirst($cStatus)) ?></span>
                    </td>
                    <td>
                        <?php if (!empty($comment['post_title'])): ?>
                            <a href="<?= url('admin/posts/' . (int) ($comment['post_id'] ?? 0) . '/edit') ?>">
                                <?= h(mb_strimwidth($comment['post_title'], 0, 40, '...')) ?>
                            </a>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td><?= h(date('M j, Y', strtotime($comment['created_at']))) ?></td>
                    <td class="actions">
                        <?php if (($comment['status'] ?? '') !== 'approved'): ?>
                            <form method="POST" action="<?= url('admin/comments/' . (int) $comment['id'] . '/approve') ?>">
                                <?= \App\Core\Csrf::field() ?>
                                <button type="submit" class="btn btn-sm btn-approve">Approve</button>
                            </form>
                        <?php endif; ?>

                        <?php if (($comment['status'] ?? '') !== 'spam'): ?>
                            <form method="POST" action="<?= url('admin/comments/' . (int) $comment['id'] . '/spam') ?>">
                                <?= \App\Core\Csrf::field() ?>
                                <button type="submit" class="btn btn-sm btn-spam">Spam</button>
                            </form>
                        <?php endif; ?>

                        <form method="POST" action="<?= url('admin/comments/' . (int) $comment['id'] . '/delete') ?>" onsubmit="return confirm('Delete this comment?');">
                            <?= \App\Core\Csrf::field() ?>
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($paginator->hasPages()): ?>
        <?php $queryParams = $status ? ['status' => $status] : []; ?>
        <div class="pagination">
            <?php if ($paginator->hasPrev()): ?>
                <a href="<?= url('admin/comments?' . http_build_query(array_merge($queryParams, ['page' => $paginator->prevPage()]))) ?>">Prev</a>
            <?php else: ?>
                <span class="disabled">Prev</span>
            <?php endif; ?>

            <?php foreach ($paginator->pages() as $page): ?>
                <?php if ($page === '...'): ?>
                    <span class="ellipsis">...</span>
                <?php elseif ($page === $paginator->currentPage): ?>
                    <span class="active"><?= $page ?></span>
                <?php else: ?>
                    <a href="<?= url('admin/comments?' . http_build_query(array_merge($queryParams, ['page' => $page]))) ?>"><?= $page ?></a>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($paginator->hasNext()): ?>
                <a href="<?= url('admin/comments?' . http_build_query(array_merge($queryParams, ['page' => $paginator->nextPage()]))) ?>">Next</a>
            <?php else: ?>
                <span class="disabled">Next</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div class="empty-state">
        <p>No comments found.</p>
    </div>
<?php endif; ?>
