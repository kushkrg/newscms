<?php
/**
 * @var array|null $categories  Array of category rows (each with 'name', 'slug', 'post_count')
 * @var array|null $tags        Array of tag rows (each with 'name', 'slug', 'post_count')
 * @var array|null $archive     Array of archive rows (each with 'year', 'month', 'month_name', 'post_count')
 */
$categories = $categories ?? [];
$tags = $tags ?? [];
$archive = $archive ?? [];
?>
<aside class="sidebar">

    <?php $sidebarAd = \App\Core\Ad::render('sidebar'); ?>
    <?php if ($sidebarAd): ?>
    <div class="sidebar-widget">
        <?= $sidebarAd ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($categories)): ?>
    <div class="sidebar-widget">
        <h4 class="sidebar-widget__title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            Categories
        </h4>
        <ul class="sidebar-category-list">
            <?php foreach ($categories as $cat): ?>
                <li>
                    <a href="<?= url('category/' . h($cat['slug'])) ?>" class="sidebar-category-link">
                        <span class="sidebar-category-name"><?= h($cat['name']) ?></span>
                        <?php if (isset($cat['post_count'])): ?>
                            <span class="sidebar-category-count"><?= (int) $cat['post_count'] ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if (!empty($tags)): ?>
    <div class="sidebar-widget">
        <h4 class="sidebar-widget__title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
            Popular Tags
        </h4>
        <div class="sidebar-tag-cloud">
            <?php foreach ($tags as $tag): ?>
                <a href="<?= url('tag/' . h($tag['slug'])) ?>" class="sidebar-tag">
                    <span class="sidebar-tag__hash">#</span><?= h($tag['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($archive)): ?>
    <div class="sidebar-widget">
        <h4 class="sidebar-widget__title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Archive
        </h4>
        <ul class="sidebar-archive-list">
            <?php foreach ($archive as $item): ?>
                <li>
                    <a href="<?= url('archive/' . h($item['year']) . '/' . h($item['month'])) ?>" class="sidebar-archive-link">
                        <span><?= h($item['month_name']) ?> <?= h($item['year']) ?></span>
                        <span class="sidebar-archive-count"><?= (int) $item['post_count'] ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="sidebar-widget sidebar-newsletter">
        <div class="sidebar-newsletter__icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </div>
        <h4 class="sidebar-newsletter__title">Stay Updated</h4>
        <p class="sidebar-newsletter__text">Get the latest articles delivered straight to your inbox. No spam, ever.</p>

        <?php $nlSuccess = \App\Core\Session::getFlash('newsletter_success'); ?>
        <?php $nlError = \App\Core\Session::getFlash('newsletter_error'); ?>

        <?php if ($nlSuccess): ?>
        <div class="sidebar-newsletter__alert sidebar-newsletter__alert--success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span><?= h($nlSuccess) ?></span>
        </div>
        <?php elseif ($nlError): ?>
        <div class="sidebar-newsletter__alert sidebar-newsletter__alert--error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <span><?= h($nlError) ?></span>
        </div>
        <?php endif; ?>

        <form class="sidebar-newsletter__form" action="<?= url('newsletter/subscribe') ?>" method="POST">
            <?= \App\Core\Csrf::field() ?>
            <label for="newsletter-email" class="sr-only">Email address</label>
            <input
                type="email"
                id="newsletter-email"
                name="email"
                class="sidebar-newsletter__input"
                placeholder="your@email.com"
                required
                autocomplete="email"
            >
            <?= \App\Core\Recaptcha::field('subscribe') ?>
            <button type="submit" class="sidebar-newsletter__btn">
                Subscribe
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </button>
        </form>
    </div>

</aside>
