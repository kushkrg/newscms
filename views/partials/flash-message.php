<?php
$flashSuccess = \App\Core\Session::getFlash('success');
$flashError = \App\Core\Session::getFlash('error');
?>

<?php if ($flashSuccess): ?>
<div class="flash-message flash-message--success" role="alert">
    <div class="container">
        <div class="flash-message__inner">
            <svg class="flash-message__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <span class="flash-message__text"><?= h($flashSuccess) ?></span>
            <button type="button" class="flash-message__close" onclick="this.closest('.flash-message').remove()" aria-label="Dismiss message">&times;</button>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($flashError): ?>
<div class="flash-message flash-message--error" role="alert">
    <div class="container">
        <div class="flash-message__inner">
            <svg class="flash-message__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <span class="flash-message__text"><?= h($flashError) ?></span>
            <button type="button" class="flash-message__close" onclick="this.closest('.flash-message').remove()" aria-label="Dismiss message">&times;</button>
        </div>
    </div>
</div>
<?php endif; ?>
