<?php
/**
 * Home / Blog Listing Page
 *
 * @var array       $featured   Array of featured posts
 * @var array       $posts      Array of post rows
 * @var \App\Core\Paginator $paginator
 * @var array       $categories Array of categories with post_count
 * @var array       $tags       Array of tags
 * @var \App\Core\SEO $seo
 * @var int         $currentPage
 */

$featured   = $featured ?? [];
$posts      = $posts ?? [];
$categories = $categories ?? [];
$tags       = $tags ?? [];
$isFirstPage = ($currentPage ?? 1) === 1;
?>

<?php // ─── Hero Section (Featured post, first page only) ─── ?>
<?php if (!empty($featured) && $isFirstPage):
    $hero = $featured[0];
    $heroUrl = url('article/' . $hero['slug']);
    $heroDate = !empty($hero['published_at']) ? date('M j, Y', strtotime($hero['published_at'])) : '';
    $heroReadingTime = !empty($hero['reading_time_mins']) ? (int) $hero['reading_time_mins'] : null;
    $hasHeroImage = !empty($hero['featured_image']);
?>
<section class="hero <?= $hasHeroImage ? '' : 'hero--no-image' ?>">
    <?php if ($hasHeroImage): ?>
        <div class="hero__image">
            <img src="<?= upload_url($hero['featured_image']) ?>"
                 alt="<?= h($hero['title']) ?>"
                 loading="eager">
        </div>
        <div class="hero__overlay"></div>
    <?php endif; ?>
    <div class="container">
        <div class="hero__content">
            <?php if (!empty($hero['category_name'])): ?>
                <a href="<?= url('category/' . h($hero['category_slug'] ?? '')) ?>" class="hero__category">
                    <?= h($hero['category_name']) ?>
                </a>
            <?php endif; ?>

            <h1 class="hero__title">
                <a href="<?= h($heroUrl) ?>"><?= h($hero['title']) ?></a>
            </h1>

            <?php if (!empty($hero['excerpt'])): ?>
                <p class="hero__excerpt"><?= h($hero['excerpt']) ?></p>
            <?php endif; ?>

            <div class="hero__meta">
                <?php if (!empty($hero['author_name'])): ?>
                    <span><?= h($hero['author_name']) ?></span>
                <?php endif; ?>
                <?php if ($heroDate): ?>
                    <span>
                        <time datetime="<?= h($hero['published_at']) ?>"><?= h($heroDate) ?></time>
                    </span>
                <?php endif; ?>
                <?php if ($heroReadingTime): ?>
                    <span><?= $heroReadingTime ?> min read</span>
                <?php endif; ?>
            </div>

            <a href="<?= h($heroUrl) ?>" class="hero__cta">
                Read Article
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php // ─── Category Filter Bar ─── ?>
<?php if (!empty($categories)): ?>
<nav class="section" style="padding-bottom: 0;" aria-label="Category filter">
    <div class="container">
        <div style="display: flex; gap: var(--space-md); overflow-x: auto; padding-bottom: var(--space-sm); -webkit-overflow-scrolling: touch;">
            <a href="<?= url('/') ?>" class="tag-badge" style="white-space: nowrap;">All</a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?= url('category/' . h($cat['slug'])) ?>"
                   class="tag-badge"
                   style="white-space: nowrap;">
                    <?= h($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</nav>
<?php endif; ?>

<?php // ─── Post Grid + Sidebar ─── ?>
<section class="section">
    <div class="container">
        <?php if (!empty($posts)): ?>
            <div class="grid-content">
                <div>
                    <h2 class="section-title">Latest Articles</h2>
                    <div class="post-grid">
                        <?php foreach ($posts as $idx => $p): ?>
                            <?= \App\Core\View::partial('partials/post-card', ['post' => $p]) ?>
                            <?php if (($idx + 1) % 3 === 0 && ($idx + 1) < count($posts)): ?>
                                <?= \App\Core\Ad::render('between_posts', 'ad-container--in-feed') ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <?php if (isset($paginator)): ?>
                        <?= \App\Core\View::partial('partials/pagination', [
                            'paginator' => $paginator,
                            'baseUrl'   => url(''),
                        ]) ?>
                    <?php endif; ?>
                </div>

                <?= \App\Core\View::partial('partials/sidebar', [
                    'categories' => $categories,
                    'tags'       => $tags,
                ]) ?>
            </div>
        <?php else: ?>
            <?php // ─── Empty State ─── ?>
            <div class="text-center" style="padding: var(--space-3xl) 0;">
                <h2 style="font-family: var(--font-serif); margin-bottom: var(--space-md);">No articles yet</h2>
                <p class="text-muted">Check back soon for new content.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
