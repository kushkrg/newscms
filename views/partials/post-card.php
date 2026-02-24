<?php
/** @var array $post */
$postUrl = url($post['slug']);
$hasImage = !empty($post['featured_image']);
$readingTime = !empty($post['reading_time_mins']) ? (int) $post['reading_time_mins'] : null;
$publishedDate = !empty($post['published_at']) ? date('M j, Y', strtotime($post['published_at'])) : '';
?>
<article class="post-card">

    <?php if ($hasImage): ?>
        <a href="<?= h($postUrl) ?>" class="post-card__image-link">
            <div class="post-card__image-wrapper">
                <img
                    src="<?= upload_url($post['featured_image']) ?>"
                    alt="<?= h($post['title']) ?>"
                    class="post-card__image"
                    loading="lazy"
                    decoding="async"
                >
            </div>
        </a>
    <?php endif; ?>

    <div class="post-card__body">

        <?php if (!empty($post['category_name'])): ?>
            <a href="<?= url('category/' . h($post['category_slug'] ?? '')) ?>" class="post-card__category">
                <?= h($post['category_name']) ?>
            </a>
        <?php endif; ?>

        <h3 class="post-card__title">
            <a href="<?= h($postUrl) ?>"><?= h($post['title']) ?></a>
        </h3>

        <?php if (!empty($post['excerpt'])): ?>
            <p class="post-card__excerpt"><?= h($post['excerpt']) ?></p>
        <?php endif; ?>

        <div class="post-card__meta">
            <?php if (!empty($post['author_name'])): ?>
                <span class="post-card__author"><?= h($post['author_name']) ?></span>
            <?php endif; ?>

            <?php if ($publishedDate): ?>
                <time class="post-card__date" datetime="<?= h($post['published_at']) ?>">
                    <?= h($publishedDate) ?>
                </time>
            <?php endif; ?>

            <?php if ($readingTime): ?>
                <span class="post-card__reading-time"><?= (int) $readingTime ?> min read</span>
            <?php endif; ?>
        </div>

    </div>

</article>
