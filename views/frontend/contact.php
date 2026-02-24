<?php
/**
 * Contact Page with Form
 *
 * @var array|null     $page  Page row from DB (may be null if no 'contact' page exists)
 * @var array          $old   Flashed old input values
 * @var \App\Core\SEO  $seo
 */

$old = $old ?? [];
?>

<?php // ─── Breadcrumb ─── ?>
<div class="container">
    <?= \App\Core\View::partial('partials/breadcrumb', ['seo' => $seo]) ?>
</div>

<section class="section">
    <div class="container-narrow">

        <header class="text-center" style="margin-bottom: var(--space-2xl);">
            <h1><?= h($pageTitle ?? 'Contact Us') ?></h1>
            <p class="text-muted" style="max-width: 520px; margin: var(--space-sm) auto 0;">
                Have a question, suggestion, or just want to say hello? Fill out the form below and we'll get back to you as soon as possible.
            </p>
        </header>

        <?php // Show the page content from DB if it exists (above the form) ?>
        <?php if (!empty($page['content'])): ?>
            <div class="prose" style="margin-bottom: var(--space-2xl);">
                <?= $page['content'] ?>
            </div>
        <?php endif; ?>

        <?php
        $contactSuccess = \App\Core\Session::getFlash('contact_success');
        $contactError   = \App\Core\Session::getFlash('error');
        ?>

        <?php if ($contactSuccess): ?>
            <!-- Beautiful success card replaces the form -->
            <div class="contact-success-card">
                <div class="success-icon-ring">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <h2>Thank You!</h2>
                <p>Your message has been sent successfully. We'll get back to you as soon as possible.</p>
                <a href="<?= url('/') ?>" class="btn-outline">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Back to Home
                </a>
            </div>
        <?php else: ?>
            <?php if ($contactError): ?>
                <div class="flash-message flash-message--error" role="alert" style="max-width: 640px; margin: 0 auto var(--space-lg);">
                    <div class="flash-message__inner">
                        <svg class="flash-message__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                        <span class="flash-message__text"><?= h($contactError) ?></span>
                        <button type="button" class="flash-message__close" onclick="this.closest('.flash-message').remove()" aria-label="Dismiss">&times;</button>
                    </div>
                </div>
            <?php endif; ?>

            <div class="contact-form-wrapper">
                <form action="<?= url('contact') ?>" method="POST" class="contact-form">
                    <?= \App\Core\Csrf::field() ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact-name">Full Name <span aria-hidden="true">*</span></label>
                            <input type="text"
                                   name="name"
                                   id="contact-name"
                                   class="form-input"
                                   required
                                   maxlength="100"
                                   placeholder="John Doe"
                                   autocomplete="name"
                                   value="<?= h($old['name'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="contact-email">Email Address <span aria-hidden="true">*</span></label>
                            <input type="email"
                                   name="email"
                                   id="contact-email"
                                   class="form-input"
                                   required
                                   maxlength="255"
                                   placeholder="john@example.com"
                                   autocomplete="email"
                                   value="<?= h($old['email'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact-subject">Subject <span aria-hidden="true">*</span></label>
                        <input type="text"
                               name="subject"
                               id="contact-subject"
                               class="form-input"
                               required
                               maxlength="200"
                               placeholder="What is this about?"
                               value="<?= h($old['subject'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="contact-message">Message <span aria-hidden="true">*</span></label>
                        <textarea name="message"
                                  id="contact-message"
                                  class="form-textarea"
                                  required
                                  maxlength="5000"
                                  rows="6"
                                  placeholder="Write your message here..."><?= h($old['message'] ?? '') ?></textarea>
                    </div>

                    <?= \App\Core\Recaptcha::field('contact') ?>

                    <div style="text-align: right; margin-top: var(--space-md);">
                        <button type="submit" class="btn btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px; vertical-align: -2px;"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

    </div>
</section>
