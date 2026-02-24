<?php
/**
 * @var \App\Core\SEO $seo
 */
if (!isset($seo) || !($seo instanceof \App\Core\SEO)) {
    return;
}

$breadcrumbs = $seo->getBreadcrumbs();
if (empty($breadcrumbs)) {
    return;
}
$lastIndex = count($breadcrumbs) - 1;
?>
<nav class="breadcrumb" aria-label="Breadcrumb">
    <ol class="breadcrumb__list">
        <?php foreach ($breadcrumbs as $i => $crumb): ?>
            <?php if ($i === $lastIndex): ?>
                <li class="breadcrumb__item breadcrumb__item--active" aria-current="page">
                    <span><?= h($crumb['name']) ?></span>
                </li>
            <?php else: ?>
                <li class="breadcrumb__item">
                    <a href="<?= h($crumb['url']) ?>" class="breadcrumb__link"><?= h($crumb['name']) ?></a>
                    <span class="breadcrumb__separator" aria-hidden="true">/</span>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>
