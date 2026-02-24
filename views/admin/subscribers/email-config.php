<style>
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .admin-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #000; }
    .btn { display: inline-block; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; text-decoration: none; border: 1px solid #000; cursor: pointer; transition: all 0.15s ease; }
    .btn-primary { background: #000; color: #fff; }
    .btn-primary:hover { background: #222; }
    .btn-outline { background: #fff; color: #000; border: 1px solid #ccc; }
    .btn-outline:hover { border-color: #000; }
    .config-form { max-width: 560px; }
    .form-group { display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 1.25rem; }
    .form-group label { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #333; }
    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group input[type="password"],
    .form-group input[type="email"],
    .form-group select { padding: 0.55rem 0.75rem; border: 1px solid #ccc; font-size: 0.9rem; background: #fff; color: #000; width: 100%; box-sizing: border-box; font-family: inherit; }
    .form-group input:focus,
    .form-group select:focus { outline: none; border-color: #000; }
    .form-group .note { font-size: 0.75rem; color: #999; margin-top: 0.15rem; }
    .section-title { font-size: 1rem; font-weight: 700; color: #000; padding-bottom: 0.5rem; border-bottom: 2px solid #000; margin-bottom: 1.25rem; margin-top: 2rem; text-transform: uppercase; letter-spacing: 0.04em; font-size: 0.85rem; }
    .section-title:first-of-type { margin-top: 0; }
    .form-actions { display: flex; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e5e5; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }
    .info-box { background: #f5f5f5; border: 1px solid #e5e5e5; padding: 1rem; margin-bottom: 1.5rem; font-size: 0.85rem; color: #555; line-height: 1.5; }
    .info-box strong { color: #000; }
</style>

<div class="admin-header">
    <h1><?= h($pageTitle) ?></h1>
    <a href="<?= url('admin/subscribers') ?>" class="btn btn-outline">Back to Subscribers</a>
</div>

<div class="info-box">
    <strong>SMTP Configuration:</strong> Configure your email server settings below. If no SMTP server is configured, the system will attempt to use PHP's built-in <code>mail()</code> function as a fallback. For reliable delivery, we recommend using an SMTP service like Gmail, SendGrid, or Mailgun.
</div>

<form method="POST" action="<?= url('admin/subscribers/email-config/save') ?>" class="config-form">
    <?= \App\Core\Csrf::field() ?>

    <div class="section-title">SMTP Server</div>

    <div class="form-row">
        <div class="form-group">
            <label for="smtp_host">SMTP Host</label>
            <input type="text" id="smtp_host" name="smtp_host" value="<?= h($settings['smtp_host'] ?? '') ?>" placeholder="e.g. smtp.gmail.com">
        </div>
        <div class="form-group">
            <label for="smtp_port">SMTP Port</label>
            <input type="number" id="smtp_port" name="smtp_port" value="<?= h($settings['smtp_port'] ?? '587') ?>" placeholder="587">
            <span class="note">Common: 587 (TLS), 465 (SSL), 25 (none)</span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="smtp_user">Username</label>
            <input type="text" id="smtp_user" name="smtp_user" value="<?= h($settings['smtp_user'] ?? '') ?>" placeholder="your@email.com">
        </div>
        <div class="form-group">
            <label for="smtp_pass">Password</label>
            <input type="password" id="smtp_pass" name="smtp_pass" value="<?= h($settings['smtp_pass'] ?? '') ?>" placeholder="App password or SMTP password">
        </div>
    </div>

    <div class="form-group">
        <label for="smtp_encryption">Encryption</label>
        <select id="smtp_encryption" name="smtp_encryption">
            <option value="tls" <?= ($settings['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS (Recommended)</option>
            <option value="ssl" <?= ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
            <option value="none" <?= ($settings['smtp_encryption'] ?? '') === 'none' ? 'selected' : '' ?>>None</option>
        </select>
    </div>

    <div class="section-title">Sender Information</div>

    <div class="form-row">
        <div class="form-group">
            <label for="smtp_from_email">From Email</label>
            <input type="email" id="smtp_from_email" name="smtp_from_email" value="<?= h($settings['smtp_from_email'] ?? '') ?>" placeholder="noreply@yoursite.com">
        </div>
        <div class="form-group">
            <label for="smtp_from_name">From Name</label>
            <input type="text" id="smtp_from_name" name="smtp_from_name" value="<?= h($settings['smtp_from_name'] ?? '') ?>" placeholder="Your Site Name">
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Configuration</button>
        <a href="<?= url('admin/subscribers') ?>" class="btn btn-outline">Cancel</a>
    </div>
</form>
