<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #000; }
    .btn { display: inline-block; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; border: 1px solid #000; cursor: pointer; transition: all 0.15s ease; }
    .btn-primary { background: #000; color: #fff; }
    .btn-primary:hover { background: #222; }
    .btn-outline { background: #fff; color: #000; border: 1px solid #ccc; }
    .btn-outline:hover { border-color: #000; }

    .compose-layout { display: grid; grid-template-columns: 1fr 340px; gap: 2rem; }
    @media (max-width: 900px) { .compose-layout { grid-template-columns: 1fr; } }

    .compose-form { }
    .form-group { display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 1.25rem; }
    .form-group label { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #333; }
    .form-group input[type="text"],
    .form-group textarea { padding: 0.55rem 0.75rem; border: 1px solid #ccc; font-size: 0.9rem; background: #fff; color: #000; width: 100%; box-sizing: border-box; font-family: inherit; }
    .form-group input:focus,
    .form-group textarea:focus { outline: none; border-color: #000; }
    .form-group textarea { height: 280px; resize: vertical; line-height: 1.6; }
    .form-group .note { font-size: 0.75rem; color: #999; margin-top: 0.15rem; }
    .form-actions { display: flex; gap: 0.75rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e5e5; align-items: center; }

    .info-card { background: #f9f9f9; border: 1px solid #e5e5e5; padding: 1.25rem; margin-bottom: 1.5rem; }
    .info-card__title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #333; margin-bottom: 0.75rem; }
    .info-card__number { font-size: 2rem; font-weight: 700; color: #000; line-height: 1; }
    .info-card__label { font-size: 0.8rem; color: #666; margin-top: 0.25rem; }

    .log-section { }
    .log-section__title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #333; margin-bottom: 0.75rem; }
    .log-item { padding: 0.75rem 0; border-bottom: 1px solid #eee; }
    .log-item:last-child { border-bottom: none; }
    .log-item__subject { font-weight: 600; font-size: 0.85rem; color: #000; }
    .log-item__meta { font-size: 0.75rem; color: #999; margin-top: 0.15rem; }
    .empty-log { text-align: center; padding: 1.5rem; color: #999; font-size: 0.85rem; }
</style>

<div class="admin-header">
    <h1><?= h($pageTitle) ?></h1>
    <a href="<?= url('admin/subscribers') ?>" class="btn btn-outline">Back to Subscribers</a>
</div>

<div class="compose-layout">
    <div class="compose-form">
        <form method="POST" action="<?= url('admin/subscribers/send') ?>">
            <?= \App\Core\Csrf::field() ?>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" value="<?= h(\App\Core\Session::getFlash('old')['subject'] ?? '') ?>" placeholder="Enter email subject..." required>
            </div>

            <div class="form-group">
                <label for="body">Message Body</label>
                <textarea id="body" name="body" placeholder="Write your email content here..."><?= h(\App\Core\Session::getFlash('old')['body'] ?? '') ?></textarea>
                <span class="note">Plain text. Line breaks will be converted to HTML. An unsubscribe link is automatically appended.</span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Send this email to <?= (int) $activeCount ?> active subscriber(s)?');">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: -2px; margin-right: 4px;"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Send to <?= (int) $activeCount ?> Subscriber(s)
                </button>
                <a href="<?= url('admin/subscribers') ?>" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>

    <div class="compose-sidebar">
        <div class="info-card">
            <div class="info-card__title">Recipients</div>
            <div class="info-card__number"><?= (int) $activeCount ?></div>
            <div class="info-card__label">Active subscribers will receive this email</div>
        </div>

        <div class="log-section">
            <div class="log-section__title">Recent Emails Sent</div>
            <?php if (!empty($emailLogs)): ?>
                <?php foreach ($emailLogs as $log): ?>
                    <div class="log-item">
                        <div class="log-item__subject"><?= h($log['subject'] ?? '') ?></div>
                        <div class="log-item__meta">
                            <?= h(date('M j, Y g:ia', strtotime($log['sent_at']))) ?>
                            &middot; <?= (int) $log['recipient_count'] ?> recipient(s)
                            <?php if (!empty($log['sender_name'])): ?>
                                &middot; by <?= h($log['sender_name']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-log">No emails sent yet.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
