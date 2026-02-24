<?php
/**
 * Admin Messages Listing
 *
 * @var array  $messages
 * @var string|null $status
 * @var \App\Core\Paginator $paginator
 * @var array  $counts
 */
$messages  = $messages ?? [];
$status    = $status ?? null;
$counts    = $counts ?? [];
?>

<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #000; }
    .status-tabs { display: flex; gap: 0; margin-bottom: 1.5rem; border-bottom: 2px solid #e5e5e5; }
    .status-tabs a { padding: 0.6rem 1.25rem; font-size: 0.85rem; font-weight: 500; text-decoration: none; color: #666; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all 0.15s ease; display: flex; align-items: center; gap: 6px; }
    .status-tabs a:hover { color: #000; }
    .status-tabs a.active { color: #000; font-weight: 700; border-bottom-color: #000; }
    .tab-count { background: #f0f0f0; color: #666; font-size: 0.7rem; padding: 1px 6px; border-radius: 10px; font-weight: 600; }
    .status-tabs a.active .tab-count { background: #000; color: #fff; }
    .admin-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    .admin-table th { text-align: left; padding: 0.75rem 1rem; border-bottom: 2px solid #000; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #333; }
    .admin-table td { padding: 0.75rem 1rem; border-bottom: 1px solid #e5e5e5; vertical-align: top; }
    .admin-table tr:hover { background: #fafafa; }
    .admin-table a { color: #000; text-decoration: none; font-weight: 500; }
    .admin-table a:hover { text-decoration: underline; }
    .msg-name { font-weight: 600; }
    .msg-email { font-size: 0.8rem; color: #999; }
    .msg-subject { font-weight: 500; }
    .msg-subject a { display: block; }
    .msg-excerpt { color: #777; font-size: 0.82rem; margin-top: 2px; line-height: 1.4; }
    .msg-row--unread { background: #fafbff; }
    .msg-row--unread .msg-subject { font-weight: 700; }
    .actions { display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; }
    .actions form { margin: 0; display: inline; }
    .btn { display: inline-flex; align-items: center; gap: 4px; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; border: 1px solid #000; cursor: pointer; transition: all 0.15s ease; background: #fff; color: #000; }
    .btn-sm { padding: 0.3rem 0.6rem; font-size: 0.8rem; }
    .btn-primary { background: #000; color: #fff; }
    .btn-primary:hover { background: #222; }
    .btn-danger { border-color: #999; }
    .btn-danger:hover { background: #000; color: #fff; }
    .badge { display: inline-block; padding: 0.15rem 0.5rem; font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; border-radius: 3px; }
    .badge-unread { background: #dbeafe; color: #1d4ed8; }
    .badge-read { background: #e5e5e5; color: #555; }
    .badge-replied { background: #d1fae5; color: #065f46; }
    .unread-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #3b82f6; flex-shrink: 0; margin-right: 4px; }
    .pagination { display: flex; gap: 0.25rem; align-items: center; justify-content: center; margin-top: 2rem; }
    .pagination a, .pagination span { display: inline-block; padding: 0.4rem 0.75rem; font-size: 0.85rem; border: 1px solid #e5e5e5; text-decoration: none; color: #000; }
    .pagination a:hover { border-color: #000; background: #000; color: #fff; }
    .pagination .active { background: #000; color: #fff; border-color: #000; }
    .pagination .disabled { color: #ccc; pointer-events: none; }
    .empty-state { text-align: center; padding: 3rem 1rem; color: #999; font-size: 0.95rem; }
</style>

<div class="admin-header">
    <h1><?= h($pageTitle) ?></h1>
</div>

<div class="status-tabs">
    <a href="<?= url('admin/messages') ?>" class="<?= empty($status) ? 'active' : '' ?>">
        All <span class="tab-count"><?= (int) ($counts['all'] ?? 0) ?></span>
    </a>
    <a href="<?= url('admin/messages?status=unread') ?>" class="<?= $status === 'unread' ? 'active' : '' ?>">
        Unread <span class="tab-count"><?= (int) ($counts['unread'] ?? 0) ?></span>
    </a>
    <a href="<?= url('admin/messages?status=read') ?>" class="<?= $status === 'read' ? 'active' : '' ?>">
        Read <span class="tab-count"><?= (int) ($counts['read'] ?? 0) ?></span>
    </a>
    <a href="<?= url('admin/messages?status=replied') ?>" class="<?= $status === 'replied' ? 'active' : '' ?>">
        Replied <span class="tab-count"><?= (int) ($counts['replied'] ?? 0) ?></span>
    </a>
</div>

<?php if (!empty($messages)): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 3%"></th>
                <th>From</th>
                <th>Subject &amp; Message</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $msg): ?>
                <?php $isUnread = ($msg['status'] ?? '') === 'unread'; ?>
                <tr class="<?= $isUnread ? 'msg-row--unread' : '' ?>">
                    <td><?= $isUnread ? '<span class="unread-dot" title="Unread"></span>' : '' ?></td>
                    <td>
                        <div class="msg-name"><?= h($msg['name'] ?? '') ?></div>
                        <div class="msg-email"><?= h($msg['email'] ?? '') ?></div>
                    </td>
                    <td>
                        <div class="msg-subject">
                            <a href="<?= url('admin/messages/' . (int) $msg['id']) ?>">
                                <?= h($msg['subject'] ?? '(No subject)') ?>
                            </a>
                        </div>
                        <div class="msg-excerpt"><?= h(mb_strimwidth($msg['message'] ?? '', 0, 100, '...')) ?></div>
                    </td>
                    <td>
                        <?php
                        $badgeClass = match ($msg['status'] ?? 'unread') {
                            'read'    => 'badge-read',
                            'replied' => 'badge-replied',
                            default   => 'badge-unread',
                        };
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= h(ucfirst($msg['status'] ?? 'unread')) ?></span>
                    </td>
                    <td style="white-space: nowrap;"><?= h(date('M j, Y', strtotime($msg['created_at']))) ?></td>
                    <td class="actions">
                        <a href="<?= url('admin/messages/' . (int) $msg['id']) ?>" class="btn btn-sm btn-primary">View</a>
                        <form method="POST" action="<?= url('admin/messages/' . (int) $msg['id'] . '/delete') ?>" onsubmit="return confirm('Delete this message?');">
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
                <a href="<?= url('admin/messages?' . http_build_query(array_merge($queryParams, ['page' => $paginator->prevPage()]))) ?>">Prev</a>
            <?php else: ?>
                <span class="disabled">Prev</span>
            <?php endif; ?>

            <?php foreach ($paginator->pages() as $page): ?>
                <?php if ($page === '...'): ?>
                    <span class="ellipsis">...</span>
                <?php elseif ($page === $paginator->currentPage): ?>
                    <span class="active"><?= $page ?></span>
                <?php else: ?>
                    <a href="<?= url('admin/messages?' . http_build_query(array_merge($queryParams, ['page' => $page]))) ?>"><?= $page ?></a>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($paginator->hasNext()): ?>
                <a href="<?= url('admin/messages?' . http_build_query(array_merge($queryParams, ['page' => $paginator->nextPage()]))) ?>">Next</a>
            <?php else: ?>
                <span class="disabled">Next</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div class="empty-state">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
        <p>No messages found.</p>
    </div>
<?php endif; ?>
