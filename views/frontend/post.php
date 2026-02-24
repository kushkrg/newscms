<?php
/**
 * Single Post / Article Page
 *
 * @var array       $post      Post row (id, title, slug, body/content, featured_image, published_at, reading_time, allow_comments, ...)
 * @var array|null  $author    Author row (id, name, slug, avatar, bio, website, social_twitter, social_linkedin, ...)
 * @var array|null  $category  Category row (id, name, slug)
 * @var array       $tags      Array of tag rows (id, name, slug)
 * @var array       $related   Array of related post rows
 * @var array       $comments  Array of comment rows (threaded: each may have 'replies')
 * @var array       $toc       Table of contents entries (id, text, level)
 * @var \App\Core\SEO $seo
 */

$tags     = $tags ?? [];
$related  = $related ?? [];
$comments = $comments ?? [];
$toc      = $toc ?? [];

$postUrl       = url($post['slug']);
$publishedDate = !empty($post['published_at']) ? date('M j, Y', strtotime($post['published_at'])) : '';
$readingTime   = !empty($post['reading_time_mins']) ? (int) $post['reading_time_mins'] : null;
$postContent   = $post['content'] ?? '';
$allowComments = $post['allow_comments'] ?? true;

// Inject in-article ad after the 3rd paragraph
$inArticleAd = \App\Core\Ad::render('in_article', 'ad-container--in-article');
if ($inArticleAd && $postContent) {
    $parts = preg_split('/(<\/p>)/i', $postContent, -1, PREG_SPLIT_DELIM_CAPTURE);
    $pCount = 0;
    $injected = false;
    $rebuilt = '';
    for ($i = 0; $i < count($parts); $i++) {
        $rebuilt .= $parts[$i];
        if (strtolower($parts[$i]) === '</p>') {
            $pCount++;
            if ($pCount === 3 && !$injected) {
                $rebuilt .= $inArticleAd;
                $injected = true;
            }
        }
    }
    $postContent = $rebuilt;
}

/**
 * Render a single comment card, recursing into replies for threaded display.
 */
function renderCommentCard(array $comment, int $depth = 0): void
{
    $commentDate = !empty($comment['created_at']) ? date('M j, Y \a\t g:i A', strtotime($comment['created_at'])) : '';
    $replies     = $comment['replies'] ?? [];
    ?>
    <div class="comment" id="comment-<?= (int) ($comment['id'] ?? 0) ?>">
        <div class="comment__header">
            <span class="comment__author"><?= h($comment['author_name'] ?? 'Anonymous') ?></span>
            <?php if ($commentDate): ?>
                <span class="comment__date"><?= h($commentDate) ?></span>
            <?php endif; ?>
        </div>
        <div class="comment__body">
            <p><?= h($comment['content'] ?? '') ?></p>
        </div>
        <?php if (!empty($replies)): ?>
            <div class="comment__replies">
                <?php foreach ($replies as $reply): ?>
                    <?php renderCommentCard($reply, $depth + 1); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
?>

<?php // ─── Breadcrumb ─── ?>
<div class="container">
    <?= \App\Core\View::partial('partials/breadcrumb', ['seo' => $seo]) ?>
</div>

<?php // ─── Article Header ─── ?>
<header class="article-header">
    <div class="container-narrow">
        <?php if ($category): ?>
            <a href="<?= url('category/' . h($category['slug'])) ?>" class="article-header__category">
                <?= h($category['name']) ?>
            </a>
        <?php endif; ?>

        <h1 class="article-header__title"><?= h($post['title']) ?></h1>

        <div class="article-header__meta">
            <?php if ($author): ?>
                <span class="flex gap-sm" style="align-items: center;">
                    <?php if (!empty($author['avatar'])): ?>
                        <img src="<?= upload_url($author['avatar']) ?>"
                             alt="<?= h($author['name']) ?>"
                             width="28" height="28"
                             style="border-radius: 50%; object-fit: cover;">
                    <?php endif; ?>
                    <a href="<?= url('author/' . h($author['slug'] ?? '')) ?>" style="color: var(--color-gray-800); font-weight: 500; text-decoration: none;">
                        <?= h($author['name']) ?>
                    </a>
                </span>
            <?php endif; ?>

            <?php if ($publishedDate): ?>
                <span>
                    <time datetime="<?= h($post['published_at']) ?>"><?= h($publishedDate) ?></time>
                </span>
            <?php endif; ?>

            <?php if ($readingTime): ?>
                <span><?= $readingTime ?> min read</span>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($post['featured_image'])): ?>
        <div class="container" style="margin-top: var(--space-2xl);">
            <img src="<?= upload_url($post['featured_image']) ?>"
                 alt="<?= h($post['title']) ?>"
                 class="article-header__image"
                 loading="eager">
        </div>
    <?php endif; ?>
</header>

<?php // ─── Article Content + TOC Sidebar ─── ?>
<section class="article-content">
    <div class="container">
        <div class="grid-content">
            <?php // ─── Main Article Body ─── ?>
            <article class="prose">
                <?= $postContent ?>
            </article>

            <?php // ─── Sticky Table of Contents ─── ?>
            <?php if (!empty($toc)): ?>
            <aside>
                <nav class="toc" aria-label="Table of contents">
                    <h4 class="toc__title">Table of Contents</h4>
                    <ol class="toc__list">
                        <?php foreach ($toc as $heading): ?>
                            <li>
                                <a href="#<?= h($heading['id']) ?>"
                                   class="toc__link<?= ($heading['level'] ?? 2) >= 3 ? ' toc__link--nested' : '' ?>">
                                    <?= h($heading['text']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </nav>
            </aside>
            <?php else: ?>
            <aside></aside>
            <?php endif; ?>
            <?php // ─── Download File ─── ?>
<?php if (!empty($post['download_file'])): ?>
<div style="padding-bottom: var(--space-xl);">
    <div class="download-box">
        <div class="download-box__icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
        </div>
        <div class="download-box__info">
            <span class="download-box__label">Download File</span>
            <span class="download-box__name"><?= h($post['download_file_name'] ?? basename($post['download_file'])) ?></span>
        </div>
        <a href="<?= upload_url($post['download_file']) ?>"
           class="download-box__btn"
           download="<?= h($post['download_file_name'] ?? basename($post['download_file'])) ?>"
           target="_blank"
           rel="noopener">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Download
        </a>
    </div>
</div>
<?php endif; ?>
        </div>
        
    </div>
</section>

<?= \App\Core\Ad::render('after_content', 'ad-container--after-content') ?>

<?php // ─── Tags ─── ?>
<?php if (!empty($tags)): ?>
<section class="container-narrow" style="padding-bottom: var(--space-xl);">
    <div class="tag-cloud">
        <?php foreach ($tags as $tag): ?>
            <a href="<?= url('tag/' . h($tag['slug'])) ?>" class="tag-badge">
                <?= h($tag['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php // ─── Share Buttons ─── ?>
<section class="container-narrow" style="padding-bottom: var(--space-xl);">
    <div class="share-buttons">
        <span class="share-buttons__label">Share</span>

        <a href="#"
           class="share-btn"
           data-share="twitter"
           data-url="<?= h($postUrl) ?>"
           data-text="<?= h($post['title']) ?>"
           aria-label="Share on Twitter"
           rel="noopener noreferrer">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
            </svg>
        </a>

        <a href="#"
           class="share-btn"
           data-share="facebook"
           data-url="<?= h($postUrl) ?>"
           aria-label="Share on Facebook"
           rel="noopener noreferrer">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
        </a>

        <a href="#"
           class="share-btn"
           data-share="linkedin"
           data-url="<?= h($postUrl) ?>"
           data-text="<?= h($post['title']) ?>"
           aria-label="Share on LinkedIn"
           rel="noopener noreferrer">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
            </svg>
        </a>

        <button type="button"
                class="share-btn"
                data-share="copy"
                data-url="<?= h($postUrl) ?>"
                aria-label="Copy link to clipboard">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
            </svg>
        </button>
    </div>
</section>


<?php // ─── Related Posts ─── ?>
<?php if (!empty($related)): ?>
<section class="section">
    <div class="container">
        <h2 class="section-title">Related Articles</h2>
        <div class="post-grid">
            <?php foreach (array_slice($related, 0, 3) as $p): ?>
                <?= \App\Core\View::partial('partials/post-card', ['post' => $p]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php // ─── Comments Section ─── ?>
<section class="comments-section" id="comments">
    <div class="container-narrow">
        <h3 class="comments-section__title">
            <?= count($comments) ?> <?= count($comments) === 1 ? 'Comment' : 'Comments' ?>
        </h3>

        <?php if (!empty($comments)): ?>
            <div class="comments-list">
                <?php foreach ($comments as $comment): ?>
                    <?php renderCommentCard($comment); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php // ─── Comment Form ─── ?>
        <?php if ($allowComments): ?>
        <div class="comment-form">
            <h4 class="comment-form__title">Leave a Comment</h4>
            <form action="<?= url('comments/store') ?>" method="POST">
                <input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">

                <?php // Honeypot field — hidden from users, catches bots ?>
                <div style="position: absolute; left: -9999px;" aria-hidden="true">
                    <label for="website_url">Website</label>
                    <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off" value="">
                </div>

                <?= \App\Core\Csrf::field() ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="author_name">Name <span aria-hidden="true">*</span></label>
                        <input type="text"
                               name="author_name"
                               id="author_name"
                               class="form-input"
                               required
                               maxlength="100"
                               placeholder="Your name"
                               autocomplete="name">
                    </div>
                    <div class="form-group">
                        <label for="author_email">Email <span aria-hidden="true">*</span></label>
                        <input type="email"
                               name="author_email"
                               id="author_email"
                               class="form-input"
                               required
                               maxlength="255"
                               placeholder="your@email.com"
                               autocomplete="email">
                    </div>
                </div>

                <div class="form-group">
                    <label for="comment_content">Comment <span aria-hidden="true">*</span></label>
                    <textarea name="content"
                              id="comment_content"
                              class="form-textarea"
                              required
                              maxlength="2000"
                              placeholder="Write your comment..."></textarea>
                </div>

                <?= \App\Core\Recaptcha::field('comment') ?>
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
</section>
