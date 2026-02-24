<?php
/**
 * Google Ads Management Page
 *
 * @var array $ads  Key-value pairs of ad settings
 */
$ads = $ads ?? [];

$enabled       = ($ads['ads_enabled'] ?? '0') === '1';
$headerBanner  = $ads['ad_header_banner'] ?? '';
$sidebar       = $ads['ad_sidebar'] ?? '';
$inArticle     = $ads['ad_in_article'] ?? '';
$afterContent  = $ads['ad_after_content'] ?? '';
$betweenPosts  = $ads['ad_between_posts'] ?? '';
?>

<style>
/* ─── Ads Page Styles ───────────────────────────────────────── */
.settings-card {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    padding: 1.75rem 2rem;
    margin-bottom: 1.5rem;
}
.settings-card__header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
}
.settings-card__title {
    font-size: 1rem;
    font-weight: 700;
    color: #000;
    margin: 0 0 4px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.settings-card__desc {
    font-size: 0.78rem;
    color: #888;
    margin: 0;
}
.settings-card__body {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Toggle */
.toggle-switch {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
}
.toggle-switch input[type="checkbox"] {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}
.toggle-slider {
    position: relative;
    width: 44px;
    height: 24px;
    background-color: #ddd;
    border-radius: 24px;
    transition: background-color 0.2s;
    flex-shrink: 0;
}
.toggle-slider::before {
    content: '';
    position: absolute;
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: #fff;
    border-radius: 50%;
    transition: transform 0.2s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.15);
}
.toggle-switch input:checked ~ .toggle-slider {
    background-color: #000;
}
.toggle-switch input:checked ~ .toggle-slider::before {
    transform: translateX(20px);
}
.toggle-label {
    font-size: 0.88rem;
    font-weight: 600;
    color: #000;
}

/* Ad Slots Grid */
.ad-slots-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

/* Size Badge */
.ad-size-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    background: #f0f4ff;
    color: #3b5bdb;
    font-size: 0.72rem;
    font-weight: 600;
    border-radius: 6px;
    white-space: nowrap;
    flex-shrink: 0;
}

/* Code Textarea */
.code-textarea {
    font-family: 'SF Mono', Menlo, Monaco, Consolas, monospace !important;
    font-size: 0.82rem !important;
    background: #1a1a1a !important;
    color: #e0e0e0 !important;
    border-color: #333 !important;
    line-height: 1.5;
    resize: vertical;
}
.code-textarea:focus {
    background: #111 !important;
    border-color: #555 !important;
    box-shadow: 0 0 0 3px rgba(255,255,255,0.05) !important;
}
.code-textarea:hover {
    border-color: #555 !important;
}
.code-textarea::placeholder {
    color: #555;
}

/* Preview Label */
.ad-preview-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    color: #999;
    padding-top: 4px;
}

/* Hints */
.form-hint {
    font-size: 0.75rem;
    color: #999;
    margin-top: 4px;
    line-height: 1.4;
}
.form-hint strong {
    color: #555;
}

/* Size Reference Table */
.ad-sizes-table {
    overflow-x: auto;
}
.ad-sizes-table table {
    white-space: nowrap;
}
.ad-sizes-table code {
    background: #f5f5f5;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-family: 'SF Mono', Menlo, Monaco, monospace;
}

/* Responsive */
@media (max-width: 900px) {
    .ad-slots-grid {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 600px) {
    .settings-card {
        padding: 1.25rem;
    }
    .settings-card__header {
        flex-direction: column;
        gap: 8px;
    }
}
</style>

<form method="POST" action="<?= url('admin/ads/save') ?>">
    <?= \App\Core\Csrf::field() ?>

    <!-- Master Toggle -->
    <div class="settings-card" style="margin-bottom: var(--space-xl);">
        <div class="settings-card__header">
            <div>
                <h3 class="settings-card__title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    Google Ads
                </h3>
                <p class="settings-card__desc">Manage Google AdSense / Ad Manager code snippets for different positions across your site.</p>
            </div>
        </div>
        <div class="settings-card__body">
            <div class="form-group">
                <label class="toggle-switch">
                    <input type="hidden" name="settings[ads_enabled]" value="0">
                    <input type="checkbox"
                           name="settings[ads_enabled]"
                           value="1"
                           <?= $enabled ? 'checked' : '' ?>>
                    <span class="toggle-slider"></span>
                    <span class="toggle-label">Enable Ads Globally</span>
                </label>
                <p class="form-hint">Turn this off to instantly hide all ads across the site.</p>
            </div>
        </div>
    </div>

    <!-- Ad Slots -->
    <div class="ad-slots-grid">

        <!-- Header Banner -->
        <div class="settings-card">
            <div class="settings-card__header">
                <div>
                    <h3 class="settings-card__title">Header Banner</h3>
                    <p class="settings-card__desc">Appears below the navigation bar on all pages.</p>
                </div>
                <span class="ad-size-badge">728 × 90 (Leaderboard)</span>
            </div>
            <div class="settings-card__body">
                <div class="form-group">
                    <label for="ad_header_banner">Ad Code</label>
                    <textarea name="settings[ad_header_banner]"
                              id="ad_header_banner"
                              class="form-textarea code-textarea"
                              rows="6"
                              placeholder="Paste your Google AdSense or Ad Manager code here..."><?= h($headerBanner) ?></textarea>
                    <p class="form-hint">Recommended size: <strong>728×90</strong> (Leaderboard) or <strong>970×90</strong> (Large Leaderboard). Responsive ads also work well here.</p>
                </div>
                <div class="ad-preview-label">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/></svg>
                    Position: Full-width banner below header
                </div>
            </div>
        </div>

        <!-- Sidebar Ad -->
        <div class="settings-card">
            <div class="settings-card__header">
                <div>
                    <h3 class="settings-card__title">Sidebar Ad</h3>
                    <p class="settings-card__desc">Appears in the sidebar on homepage and category pages.</p>
                </div>
                <span class="ad-size-badge">300 × 250 (Medium Rectangle)</span>
            </div>
            <div class="settings-card__body">
                <div class="form-group">
                    <label for="ad_sidebar">Ad Code</label>
                    <textarea name="settings[ad_sidebar]"
                              id="ad_sidebar"
                              class="form-textarea code-textarea"
                              rows="6"
                              placeholder="Paste your Google AdSense or Ad Manager code here..."><?= h($sidebar) ?></textarea>
                    <p class="form-hint">Recommended size: <strong>300×250</strong> (Medium Rectangle) or <strong>300×600</strong> (Half Page). This ad stays visible in the sidebar.</p>
                </div>
                <div class="ad-preview-label">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="18"/></svg>
                    Position: Right sidebar widget
                </div>
            </div>
        </div>

        <!-- In-Article Ad -->
        <div class="settings-card">
            <div class="settings-card__header">
                <div>
                    <h3 class="settings-card__title">In-Article Ad</h3>
                    <p class="settings-card__desc">Inserted after the 3rd paragraph in blog posts.</p>
                </div>
                <span class="ad-size-badge">336 × 280 (Large Rectangle)</span>
            </div>
            <div class="settings-card__body">
                <div class="form-group">
                    <label for="ad_in_article">Ad Code</label>
                    <textarea name="settings[ad_in_article]"
                              id="ad_in_article"
                              class="form-textarea code-textarea"
                              rows="6"
                              placeholder="Paste your Google AdSense or Ad Manager code here..."><?= h($inArticle) ?></textarea>
                    <p class="form-hint">Recommended size: <strong>336×280</strong> (Large Rectangle) or <strong>In-article</strong> (responsive). Appears mid-content for high visibility.</p>
                </div>
                <div class="ad-preview-label">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><line x1="6" y1="13" x2="18" y2="13"/></svg>
                    Position: Inside article body (after 3rd paragraph)
                </div>
            </div>
        </div>

        <!-- After Content Ad -->
        <div class="settings-card">
            <div class="settings-card__header">
                <div>
                    <h3 class="settings-card__title">After Content Ad</h3>
                    <p class="settings-card__desc">Appears below the article content, before tags and comments.</p>
                </div>
                <span class="ad-size-badge">728 × 90 (Leaderboard)</span>
            </div>
            <div class="settings-card__body">
                <div class="form-group">
                    <label for="ad_after_content">Ad Code</label>
                    <textarea name="settings[ad_after_content]"
                              id="ad_after_content"
                              class="form-textarea code-textarea"
                              rows="6"
                              placeholder="Paste your Google AdSense or Ad Manager code here..."><?= h($afterContent) ?></textarea>
                    <p class="form-hint">Recommended size: <strong>728×90</strong> (Leaderboard) or <strong>Responsive</strong>. High engagement spot — readers finish the article here.</p>
                </div>
                <div class="ad-preview-label">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><line x1="6" y1="18" x2="18" y2="18"/></svg>
                    Position: Below article, above tags & comments
                </div>
            </div>
        </div>

        <!-- Between Posts Ad -->
        <div class="settings-card">
            <div class="settings-card__header">
                <div>
                    <h3 class="settings-card__title">Between Posts Ad (Homepage)</h3>
                    <p class="settings-card__desc">Appears in the post grid after every 3rd post on the homepage.</p>
                </div>
                <span class="ad-size-badge">728 × 90 (Leaderboard)</span>
            </div>
            <div class="settings-card__body">
                <div class="form-group">
                    <label for="ad_between_posts">Ad Code</label>
                    <textarea name="settings[ad_between_posts]"
                              id="ad_between_posts"
                              class="form-textarea code-textarea"
                              rows="6"
                              placeholder="Paste your Google AdSense or Ad Manager code here..."><?= h($betweenPosts) ?></textarea>
                    <p class="form-hint">Recommended size: <strong>728×90</strong> (Leaderboard) or <strong>Responsive In-feed</strong>. Blends naturally between post cards on the homepage.</p>
                </div>
                <div class="ad-preview-label">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="12" x2="21" y2="12"/></svg>
                    Position: Between post cards in listing grid
                </div>
            </div>
        </div>

    </div>

    <!-- Ad Size Reference -->
    <div class="settings-card" style="margin-top: var(--space-xl);">
        <div class="settings-card__header">
            <div>
                <h3 class="settings-card__title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    Common Google AdSense Sizes Reference
                </h3>
            </div>
        </div>
        <div class="settings-card__body">
            <div class="ad-sizes-table">
                <table class="data-table" style="font-size: 0.875rem;">
                    <thead>
                        <tr>
                            <th>Size (px)</th>
                            <th>Name</th>
                            <th>Best For</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>728 × 90</code></td>
                            <td>Leaderboard</td>
                            <td>Header banner, between content sections</td>
                        </tr>
                        <tr>
                            <td><code>970 × 90</code></td>
                            <td>Large Leaderboard</td>
                            <td>Wide header banners</td>
                        </tr>
                        <tr>
                            <td><code>300 × 250</code></td>
                            <td>Medium Rectangle</td>
                            <td>Sidebar, in-content</td>
                        </tr>
                        <tr>
                            <td><code>336 × 280</code></td>
                            <td>Large Rectangle</td>
                            <td>In-article, mid-content</td>
                        </tr>
                        <tr>
                            <td><code>300 × 600</code></td>
                            <td>Half Page</td>
                            <td>Sidebar (sticky)</td>
                        </tr>
                        <tr>
                            <td><code>320 × 100</code></td>
                            <td>Large Mobile Banner</td>
                            <td>Mobile header/footer</td>
                        </tr>
                        <tr>
                            <td><code>Responsive</code></td>
                            <td>Auto-size</td>
                            <td>Any position — automatically adapts to container width</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top: var(--space-xl); display: flex; justify-content: flex-end;">
        <button type="submit" class="btn btn-primary btn-lg">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Save Ad Settings
        </button>
    </div>
</form>
