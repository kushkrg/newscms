<?php
/**
 * @var \App\Core\Paginator $paginator
 * @var string $baseUrl
 */
if (!isset($paginator) || !$paginator->hasPages()) {
    return;
}

/**
 * Build a page URL from the base URL and page number.
 * Supports both /page/N (clean URLs) and ?page=N (query string) patterns.
 */
$buildPageUrl = function (int $page) use ($baseUrl): string {
    if (str_contains($baseUrl, '?')) {
        $separator = str_contains($baseUrl, 'page=') ? '' : '&';
        $url = preg_replace('/([?&])page=\d+/', '${1}page=' . $page, $baseUrl);
        if ($url === $baseUrl) {
            $url = $baseUrl . $separator . 'page=' . $page;
        }
        return $url;
    }
    $base = rtrim($baseUrl, '/');
    return $page === 1 ? $base : $base . '/page/' . $page;
};
?>
<nav class="pagination" aria-label="Pagination">
    <ul class="pagination__list">

        <?php if ($paginator->hasPrev()): ?>
            <li class="pagination__item pagination__item--prev">
                <a href="<?= h($buildPageUrl($paginator->prevPage())) ?>" class="pagination__link" rel="prev" aria-label="Previous page">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    <span>Prev</span>
                </a>
            </li>
        <?php else: ?>
            <li class="pagination__item pagination__item--prev pagination__item--disabled">
                <span class="pagination__link" aria-disabled="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    <span>Prev</span>
                </span>
            </li>
        <?php endif; ?>

        <?php foreach ($paginator->pages() as $page): ?>
            <?php if ($page === '...'): ?>
                <li class="pagination__item pagination__item--ellipsis">
                    <span class="pagination__ellipsis">&hellip;</span>
                </li>
            <?php elseif ($page === $paginator->currentPage): ?>
                <li class="pagination__item pagination__item--active">
                    <span class="pagination__link pagination__link--active" aria-current="page">
                        <?= (int) $page ?>
                    </span>
                </li>
            <?php else: ?>
                <li class="pagination__item">
                    <a href="<?= h($buildPageUrl((int) $page)) ?>" class="pagination__link">
                        <?= (int) $page ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if ($paginator->hasNext()): ?>
            <li class="pagination__item pagination__item--next">
                <a href="<?= h($buildPageUrl($paginator->nextPage())) ?>" class="pagination__link" rel="next" aria-label="Next page">
                    <span>Next</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            </li>
        <?php else: ?>
            <li class="pagination__item pagination__item--next pagination__item--disabled">
                <span class="pagination__link" aria-disabled="true">
                    <span>Next</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </span>
            </li>
        <?php endif; ?>

    </ul>
</nav>
