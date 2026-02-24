<?php
/**
 * Static Page
 *
 * @var array       $page   Page row (id, title, slug, body/content, meta_description, ...)
 * @var \App\Core\SEO $seo
 */

$pageContent = $page['content'] ?? '';
?>

<?php // ─── Breadcrumb ─── ?>
<div class="container">
    <?= \App\Core\View::partial('partials/breadcrumb', ['seo' => $seo]) ?>
</div>

<?php // ─── Page Content ─── ?>
<article class="section">
    <div class="container-narrow">
        <header class="text-center" style="margin-bottom: var(--space-2xl);">
            <h1><?= h($page['title']) ?></h1>
        </header>

        <div class="prose">
            <?= $pageContent ?>
        </div>
    </div>
</article>
