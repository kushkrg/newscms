<?php
/**
 * Archive Page
 *
 * @var array       $posts       Array of post rows
 * @var \App\Core\Paginator $paginator
 * @var array       $archiveList Array of archive rows (year, month, month_name, post_count)
 * @var int|null    $year        Filtered year (or null)
 * @var int|null    $month       Filtered month (or null)
 * @var \App\Core\SEO $seo
 */

$posts       = $posts ?? [];
$archiveList = $archiveList ?? [];
$year        = $year ?? null;
$month       = $month ?? null;

// Build a human-readable heading
$archiveHeading = 'Archive';
if ($year) {
    $archiveHeading .= ': ' . $year;
    if ($month) {
        $archiveHeading .= ' / ' . date('F', mktime(0, 0, 0, $month, 1));
    }
}

// Build the base URL for pagination
$baseArchiveUrl = 'archive';
if ($year) {
    $baseArchiveUrl .= '/' . $year;
    if ($month) {
        $baseArchiveUrl .= '/' . str_pad((string) $month, 2, '0', STR_PAD_LEFT);
    }
}
?>

<?php // ─── Breadcrumb ─── ?>
<div class="container">
    <?= \App\Core\View::partial('partials/breadcrumb', ['seo' => $seo]) ?>
</div>

<?php // ─── Archive Header ─── ?>
<header class="section" style="padding-bottom: 0;">
    <div class="container">
        <h1><?= h($archiveHeading) ?></h1>
    </div>
</header>

<hr class="separator container">

<?php // ─── Grid: Posts + Archive Sidebar ─── ?>
<section class="section" style="padding-top: 0;">
    <div class="container">
        <div class="grid-content">
            <?php // ─── Post List ─── ?>
            <div>
                <?php if (!empty($posts)): ?>
                    <div class="post-grid">
                        <?php foreach ($posts as $p): ?>
                            <?= \App\Core\View::partial('partials/post-card', ['post' => $p]) ?>
                        <?php endforeach; ?>
                    </div>

                    <?php if (isset($paginator)): ?>
                        <?= \App\Core\View::partial('partials/pagination', [
                            'paginator' => $paginator,
                            'baseUrl'   => url($baseArchiveUrl),
                        ]) ?>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="padding: var(--space-3xl) 0;">
                        <h2 style="font-family: var(--font-serif); margin-bottom: var(--space-md);">No articles found</h2>
                        <p class="text-muted" style="margin-bottom: var(--space-xl);">
                            There are no articles in this archive period.
                        </p>
                        <a href="<?= url('archive') ?>" class="btn btn-outline">View All Archives</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php // ─── Archive Sidebar ─── ?>
            <aside>
                <?php if (!empty($archiveList)): ?>
                <div class="sidebar-widget">
                    <h4 class="sidebar-widget__title">Archives</h4>
                    <ul>
                        <?php foreach ($archiveList as $item): ?>
                            <li class="archive-list__item">
                                <a href="<?= url('archive/' . h($item['year']) . '/' . h($item['month'])) ?>"
                                   class="archive-list__link">
                                    <span><?= h($item['month_name']) ?> <?= h($item['year']) ?></span>
                                    <span class="archive-list__count"><?= (int) $item['post_count'] ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>
