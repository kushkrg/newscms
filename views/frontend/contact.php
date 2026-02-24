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

    </div>
</section>
