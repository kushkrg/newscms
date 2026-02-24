<?php
/**
 * Search Results Page
 *
 * @var string      $query      The search query string
 * @var array       $posts      Array of post rows matching the query
 * @var \App\Core\Paginator|null $paginator
 * @var \App\Core\SEO $seo
 */

$query = $query ?? '';
$posts = $posts ?? [];
$total = isset($paginator) ? $paginator->total : count($posts);
?>

<?php // ─── Search Form ─── ?>
<section class="section">
    <div class="container-narrow">
        <h1 class="text-center mb-2xl">Search</h1>

        <form action="<?= url('search') ?>" method="GET" role="search">
            <div class="search-bar">
                <label for="search-input" class="visually-hidden">Search articles</label>
                <input type="search"
                       name="q"
                       id="search-input"
                       class="search-bar__input"
                       placeholder="Search articles..."
                       value="<?= h($query) ?>"
                       autocomplete="off"
                       autofocus>
                <?= \App\Core\Recaptcha::field('search') ?>
                <button type="submit" class="search-bar__button">Search</button>
            </div>
        </form>

        <?php if ($query !== ''): ?>
            <?php // ─── Results Header ─── ?>
            <p class="search-results__count">
                <?= (int) $total ?> <?= $total === 1 ? 'result' : 'results' ?> for
                &ldquo;<?= h($query) ?>&rdquo;
            </p>

            <?php if (!empty($posts)): ?>
                <?php // ─── Search Results List ─── ?>
                <div class="search-results">
                    <?php foreach ($posts as $p):
                        $resultUrl  = url($p['slug']);
                        $resultDate = !empty($p['published_at']) ? date('M j, Y', strtotime($p['published_at'])) : '';
                    ?>
                        <div class="search-result">
                            <h2 class="search-result__title">
                                <a href="<?= h($resultUrl) ?>"><?= h($p['title']) ?></a>
                            </h2>
                            <?php if (!empty($p['excerpt'])): ?>
                                <p class="search-result__excerpt"><?= h($p['excerpt']) ?></p>
                            <?php endif; ?>
                            <div class="search-result__meta">
                                <?php if (!empty($p['author_name'])): ?>
                                    <span><?= h($p['author_name']) ?></span>
                                <?php endif; ?>
                                <?php if ($resultDate): ?>
                                    <?php if (!empty($p['author_name'])): ?> &middot; <?php endif; ?>
                                    <time datetime="<?= h($p['published_at']) ?>"><?= h($resultDate) ?></time>
                                <?php endif; ?>
                                <?php if (!empty($p['category_name'])): ?>
                                    &middot;
                                    <a href="<?= url('category/' . h($p['category_slug'] ?? '')) ?>" style="text-decoration: underline;">
                                        <?= h($p['category_name']) ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php // ─── Pagination ─── ?>
                <?php if (isset($paginator)): ?>
                    <?= \App\Core\View::partial('partials/pagination', [
                        'paginator' => $paginator,
                        'baseUrl'   => url('search') . '?q=' . urlencode($query),
                    ]) ?>
                <?php endif; ?>
            <?php else: ?>
                <?php // ─── Empty State ─── ?>
                <div class="text-center" style="padding: var(--space-3xl) 0;">
                    <h2 style="font-family: var(--font-serif); margin-bottom: var(--space-md);">No results found</h2>
                    <p class="text-muted" style="margin-bottom: var(--space-xl);">
                        No results found for &ldquo;<?= h($query) ?>&rdquo;. Try a different search term.
                    </p>
                    <a href="<?= url('/') ?>" class="btn btn-outline">Back to Home</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
