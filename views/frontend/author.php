<?php
/**
 * Author Page
 *
 * @var array       $author     Author row (id, name, slug, avatar, bio, website, social_twitter, social_linkedin, social_facebook, ...)
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

<?php // ─── Author Header ─── ?>
<header class="section" style="padding-bottom: 0;">
    <div class="container-narrow text-center">
        <?php if (!empty($author['avatar'])): ?>
            <img src="<?= upload_url($author['avatar']) ?>"
                 alt="<?= h($author['name']) ?>"
                 width="96" height="96"
                 style="border-radius: 50%; object-fit: cover; margin: 0 auto var(--space-lg);">
        <?php endif; ?>

        <h1><?= h($author['name']) ?></h1>

        <?php if (!empty($author['bio'])): ?>
            <p class="text-muted mt-md" style="font-size: var(--text-lg); max-width: 600px; margin-left: auto; margin-right: auto;">
                <?= h($author['bio']) ?>
            </p>
        <?php endif; ?>

        <?php // ─── Social Links ─── ?>
        <?php
        $socialLinks = [];
        if (!empty($author['website']))        $socialLinks[] = ['url' => $author['website'], 'label' => 'Website'];
        if (!empty($author['twitter']))  $socialLinks[] = ['url' => $author['twitter'], 'label' => 'Twitter'];
        if (!empty($author['linkedin'])) $socialLinks[] = ['url' => $author['linkedin'], 'label' => 'LinkedIn'];
        ?>
        <?php if (!empty($socialLinks)): ?>
            <div class="author-bio__social mt-md" style="justify-content: center;">
                <?php foreach ($socialLinks as $link): ?>
                    <a href="<?= h($link['url']) ?>" target="_blank" rel="noopener noreferrer">
                        <?= h($link['label']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p class="text-sm text-muted mt-lg">
            <?= (int) $postCount ?> <?= $postCount === 1 ? 'article' : 'articles' ?> published
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
                    'baseUrl'   => url('author/' . $author['slug']),
                ]) ?>
            <?php endif; ?>
        <?php else: ?>
            <?php // ─── Empty State ─── ?>
            <div class="text-center" style="padding: var(--space-3xl) 0;">
                <h2 style="font-family: var(--font-serif); margin-bottom: var(--space-md);">No articles yet</h2>
                <p class="text-muted" style="margin-bottom: var(--space-xl);">
                    This author has not published any articles yet.
                </p>
                <a href="<?= url('/') ?>" class="btn btn-outline">Back to Home</a>
            </div>
        <?php endif; ?>
    </div>
</section>
