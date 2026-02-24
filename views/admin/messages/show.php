<?php
/**
 * Admin Message Detail + Reply
 *
 * @var array $message
 */
$message = $message ?? [];
$isReplied = ($message['status'] ?? '') === 'replied';
?>

<style>
    .msg-back { display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem; color: #666; text-decoration: none; margin-bottom: 1.5rem; transition: color 0.15s; }
    .msg-back:hover { color: #000; }

    .msg-card { background: #fff; border: 1px solid #e5e5e5; border-radius: 8px; overflow: hidden; margin-bottom: 1.5rem; }
    .msg-card__header { padding: 1.5rem 2rem; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; }
    .msg-card__title { font-size: 1.15rem; font-weight: 700; color: #000; margin: 0 0 8px; }
    .msg-card__meta { display: flex; flex-wrap: wrap; gap: 1.5rem; font-size: 0.82rem; color: #888; }
    .msg-card__meta-item { display: flex; align-items: center; gap: 6px; }
    .msg-card__meta-item strong { color: #333; font-weight: 600; }
    .msg-card__body { padding: 2rem; line-height: 1.7; color: #333; white-space: pre-wrap; font-size: 0.92rem; }

    .badge { display: inline-block; padding: 0.2rem 0.6rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; border-radius: 4px; }
    .badge-unread { background: #dbeafe; color: #1d4ed8; }
    .badge-read { background: #e5e5e5; color: #555; }
    .badge-replied { background: #d1fae5; color: #065f46; }

    .reply-card { background: #fff; border: 1px solid #e5e5e5; border-radius: 8px; overflow: hidden; margin-bottom: 1.5rem; }
    .reply-card__header { padding: 1.25rem 2rem; border-bottom: 1px solid #f0f0f0; }
    .reply-card__header h3 { margin: 0; font-size: 1rem; font-weight: 700; color: #000; display: flex; align-items: center; gap: 8px; }
    .reply-card__body { padding: 2rem; }

    .existing-reply { background: #f9fafb; border: 1px solid #e5e5e5; border-radius: 8px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
    .existing-reply__header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; font-size: 0.8rem; color: #888; }
    .existing-reply__header strong { color: #333; }
    .existing-reply__body { color: #333; line-height: 1.7; white-space: pre-wrap; font-size: 0.9rem; }

    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: 0.78rem; font-weight: 600; color: #333; text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 6px; }
    .form-textarea { width: 100%; padding: 12px 14px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem; font-family: inherit; min-height: 180px; resize: vertical; background: #fafafa; color: #000; box-sizing: border-box; transition: border-color 0.15s, box-shadow 0.15s; line-height: 1.6; }
    .form-textarea:focus { outline: none; border-color: #000; background: #fff; box-shadow: 0 0 0 3px rgba(0,0,0,0.06); }
    .form-hint { font-size: 0.75rem; color: #999; margin-top: 4px; }

    .btn { display: inline-flex; align-items: center; gap: 6px; padding: 0.6rem 1.25rem; font-size: 0.875rem; font-weight: 600; text-decoration: none; border: 1px solid #000; cursor: pointer; transition: all 0.15s ease; background: #fff; color: #000; border-radius: 4px; }
    .btn-primary { background: #000; color: #fff; }
    .btn-primary:hover { background: #222; }
    .btn-danger { border-color: #dc2626; color: #dc2626; }
    .btn-danger:hover { background: #dc2626; color: #fff; }

    .msg-actions { display: flex; gap: 0.75rem; align-items: center; }
    .msg-actions form { margin: 0; }

    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; padding: 1rem 2rem; border-top: 1px solid #f0f0f0; background: #fafafa; }
    .info-item { font-size: 0.8rem; }
    .info-item__label { color: #888; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.04em; font-weight: 600; margin-bottom: 2px; }
    .info-item__value { color: #333; font-weight: 500; }
    .info-item__value a { color: #000; text-decoration: none; }
    .info-item__value a:hover { text-decoration: underline; }
</style>

<a href="<?= url('admin/messages') ?>" class="msg-back">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Back to Messages
</a>

<!-- Message Card -->
<div class="msg-card">
    <div class="msg-card__header">
        <div>
            <h2 class="msg-card__title"><?= h($message['subject'] ?? '(No subject)') ?></h2>
            <div class="msg-card__meta">
                <span class="msg-card__meta-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <strong><?= h($message['name'] ?? '') ?></strong>
                </span>
                <span class="msg-card__meta-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <a href="mailto:<?= h($message['email'] ?? '') ?>"><?= h($message['email'] ?? '') ?></a>
                </span>
                <span class="msg-card__meta-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <?= h(date('M j, Y \a\t g:i A', strtotime($message['created_at']))) ?>
                </span>
            </div>
        </div>
        <div class="msg-actions">
            <?php
            $badgeClass = match ($message['status'] ?? 'unread') {
                'read'    => 'badge-read',
                'replied' => 'badge-replied',
                default   => 'badge-unread',
            };
            ?>
            <span class="badge <?= $badgeClass ?>"><?= h(ucfirst($message['status'] ?? 'unread')) ?></span>
            <form method="POST" action="<?= url('admin/messages/' . (int) $message['id'] . '/delete') ?>" onsubmit="return confirm('Delete this message permanently?');">
                <?= \App\Core\Csrf::field() ?>
                <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="msg-card__body"><?= h($message['message'] ?? '') ?></div>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-item__label">IP Address</div>
            <div class="info-item__value"><?= h($message['ip_address'] ?? 'N/A') ?></div>
        </div>
        <div class="info-item">
            <div class="info-item__label">Received</div>
            <div class="info-item__value"><?= h(date('F j, Y g:i A', strtotime($message['created_at']))) ?></div>
        </div>
        <?php if ($isReplied && !empty($message['replied_at'])): ?>
        <div class="info-item">
            <div class="info-item__label">Replied</div>
            <div class="info-item__value"><?= h(date('F j, Y g:i A', strtotime($message['replied_at']))) ?></div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Existing Reply -->
<?php if ($isReplied && !empty($message['reply_text'])): ?>
<div class="existing-reply">
    <div class="existing-reply__header">
        <span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: -2px;"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/></svg>
            Replied by <strong><?= h($message['replied_by_name'] ?? 'Admin') ?></strong>
        </span>
        <span><?= h(date('M j, Y \a\t g:i A', strtotime($message['replied_at']))) ?></span>
    </div>
    <div class="existing-reply__body"><?= h($message['reply_text']) ?></div>
</div>
<?php endif; ?>

<!-- Reply Form -->
<div class="reply-card">
    <div class="reply-card__header">
        <h3>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/></svg>
            <?= $isReplied ? 'Send Another Reply' : 'Reply to Message' ?>
        </h3>
    </div>
    <div class="reply-card__body">
        <form method="POST" action="<?= url('admin/messages/' . (int) $message['id'] . '/reply') ?>">
            <?= \App\Core\Csrf::field() ?>

            <div class="form-group">
                <label for="reply_text">Your Reply</label>
                <textarea name="reply_text" id="reply_text" class="form-textarea" placeholder="Type your reply to <?= h($message['name'] ?? '') ?>..." required></textarea>
                <p class="form-hint">This reply will be emailed to <strong><?= h($message['email'] ?? '') ?></strong> and saved in the system.</p>
            </div>

            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Send Reply
            </button>
        </form>
    </div>
</div>
