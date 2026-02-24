<?php
/**
 * Tag Listing Page
 *
 * @var array       $tag        Tag row (id, name, slug)
 * @var array       $posts      Array of post rows
 * @var \App\Core\Paginator $paginator
 * @var \App\Core\SEO $seo
 */

$posts = $posts ?? [];
$postCount = $paginator->total ?? count($posts);
?>

<?php // ─── Breadcrumb ─── ?>
<div class="container">
    <?= \App\Core\View::partial('partials/breadcrumb', ['seo' => $seo]) ?>
</div>

<?php // ─── Tag Header ─── ?>
<header class="section" style="padding-bottom: 0;">
    <div class="container-narrow text-center">
        <h6 class="text-uppercase text-muted mb-md">Tag</h6>
        <h1><?= h($tag['name']) ?></h1>
        <p class="text-sm text-muted mt-md">
            <?= (int) $postCount ?> <?= $postCount === 1 ? 'article' : 'articles' ?>
        </p>
    </div>
</header>

<hr class="separator container">

<?php // ─── Post Grid ─── ?>
<section class="section" style="padding-top: 0;">
    <div class="container">
        <?php if (!empty($posts)): ?>
            <div class="post-grid">
                <?php foreach ($posts as $p): ?>
                    <?= \App\Core\View::partial('partials/post-card', ['post' => $p]) ?>
                <?php endforeach; ?>
            </div>

            <?php if (isset($paginator)): ?>
                <?= \App\Core\View::partial('partials/pagination', [
                    'paginator' => $paginator,
                    'baseUrl'   => url('tag/' . $tag['slug']),
                ]) ?>
            <?php endif; ?>
        <?php else: ?>
            <?php // ─── Empty State ─── ?>
            <div class="text-center" style="padding: var(--space-3xl) 0;">
                <h2 style="font-family: var(--font-serif); margin-bottom: var(--space-md);">No articles found</h2>
                <p class="text-muted" style="margin-bottom: var(--space-xl);">
                    There are no articles with this tag yet.
                </p>
                <a href="<?= url('/') ?>" class="btn btn-outline">Back to Home</a>
            </div>
        <?php endif; ?>
    </div>
</section>
