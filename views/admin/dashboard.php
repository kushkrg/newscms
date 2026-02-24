<?php
/**
 * Admin Dashboard
 * Layout: layouts/admin
 *
 * Variables: $totalPosts, $publishedPosts, $totalComments, $pendingComments,
 *            $totalUsers, $recentPosts, $recentComments
 */
?>

<!-- Stats Grid -->
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-number"><?= (int) ($totalPosts ?? 0) ?></div>
        <div class="stat-label">Total Posts</div>
    </div>

    <div class="stat-card">
        <div class="stat-number"><?= (int) ($publishedPosts ?? 0) ?></div>
        <div class="stat-label">Published</div>
    </div>

    <div class="stat-card">
        <div class="stat-number"><?= (int) ($totalComments ?? 0) ?></div>
        <div class="stat-label">Comments</div>
    </div>

    <div class="stat-card">
        <div class="stat-number"><?= (int) ($pendingComments ?? 0) ?></div>
        <div class="stat-label">Pending</div>
    </div>

    <div class="stat-card">
        <div class="stat-number"><?= (int) ($totalUsers ?? 0) ?></div>
        <div class="stat-label">Users</div>
    </div>

</div>

<!-- Recent Posts Section -->
<div class="dashboard-section">
    <div class="section-header">
        <h2 class="section-title">Recent Posts</h2>
        <a href="<?= url('admin/posts') ?>" class="section-link">View all</a>
    </div>

    <?php if (!empty($recentPosts)): ?>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentPosts as $post): ?>
                <tr>
                    <td class="td-primary"><?= h($post['title'] ?? '') ?></td>
                    <td>
                        <span class="status-badge status-<?= h($post['status'] ?? 'draft') ?>">
                            <?= h(ucfirst($post['status'] ?? 'draft')) ?>
                        </span>
                    </td>
                    <td class="td-muted">
                        <?= h(date('M j, Y', strtotime($post['created_at'] ?? 'now'))) ?>
                    </td>
                    <td class="text-right">
                        <a href="<?= url('admin/posts/' . (int) ($post['id'] ?? 0) . '/edit') ?>" class="action-link">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <p>No posts yet.</p>
        <a href="<?= url('admin/posts/create') ?>" class="btn btn-primary btn-sm">Create your first post</a>
    </div>
    <?php endif; ?>
</div>

<!-- Pending Comments Section -->
<div class="dashboard-section">
    <div class="section-header">
        <h2 class="section-title">Pending Comments</h2>
        <a href="<?= url('admin/comments?status=pending') ?>" class="section-link">View all</a>
    </div>

    <?php if (!empty($recentComments)): ?>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Author</th>
                    <th>Comment</th>
                    <th>On Post</th>
                    <th>Date</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentComments as $comment): ?>
                <tr>
                    <td class="td-primary"><?= h($comment['author_name'] ?? 'Anonymous') ?></td>
                    <td class="td-muted td-excerpt"><?= h(mb_strimwidth($comment['content'] ?? '', 0, 80, '...')) ?></td>
                    <td><?= h($comment['post_title'] ?? '') ?></td>
                    <td class="td-muted">
                        <?= h(date('M j, Y', strtotime($comment['created_at'] ?? 'now'))) ?>
                    </td>
                    <td class="text-right">
                        <a href="<?= url('admin/comments/' . (int) ($comment['id'] ?? 0) . '/approve') ?>" class="action-link">Approve</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <p>No pending comments. All caught up.</p>
    </div>
    <?php endif; ?>
</div>
