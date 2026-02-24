<style>
/* ─── Settings Page ─────────────────────────────────────────── */
.settings-page { max-width: 960px; }

.settings-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}
.settings-header__left h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #000;
    margin: 0 0 0.25rem 0;
}
.settings-header__desc {
    font-size: 0.85rem;
    color: #666;
    margin: 0;
}

/* ─── Tab Navigation ────────────────────────────────────────── */
.settings-tabs {
    display: flex;
    gap: 0;
    border-bottom: 2px solid #e5e5e5;
    margin-bottom: 2rem;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.settings-tab {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0.85rem 1.25rem;
    font-size: 0.82rem;
    font-weight: 600;
    color: #888;
    text-decoration: none;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    white-space: nowrap;
    transition: color 0.15s, border-color 0.15s;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    cursor: pointer;
}
.settings-tab:hover {
    color: #333;
}
.settings-tab.active {
    color: #000;
    border-bottom-color: #000;
}
.settings-tab svg {
    flex-shrink: 0;
    opacity: 0.5;
}
.settings-tab.active svg {
    opacity: 1;
}

/* ─── Tab Panels ────────────────────────────────────────────── */
.settings-panel {
    display: none;
    animation: settingsFadeIn 0.2s ease;
}
.settings-panel.active {
    display: block;
}
@keyframes settingsFadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ─── Section Card ──────────────────────────────────────────── */
.settings-card {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    padding: 1.75rem 2rem;
    margin-bottom: 1.5rem;
}
.settings-card__header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f0f0f0;
}
.settings-card__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    flex-shrink: 0;
}
.settings-card__icon--general { background: #f0f0f0; color: #333; }
.settings-card__icon--social { background: #e8f4fd; color: #1a8cd8; }
.settings-card__icon--seo { background: #ecfdf5; color: #059669; }
.settings-card__icon--advanced { background: #fef3c7; color: #d97706; }
.settings-card__icon--security { background: #fce7f3; color: #db2777; }
.settings-card__title {
    font-size: 1rem;
    font-weight: 700;
    color: #000;
    margin: 0 0 2px 0;
}
.settings-card__subtitle {
    font-size: 0.78rem;
    color: #888;
    margin: 0;
}

/* ─── Form Fields ───────────────────────────────────────────── */
.field-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}
.field-grid .field--full {
    grid-column: 1 / -1;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.field__label {
    font-size: 0.78rem;
    font-weight: 600;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    display: flex;
    align-items: center;
    gap: 6px;
}
.field__label-hint {
    font-weight: 400;
    text-transform: none;
    letter-spacing: 0;
    color: #aaa;
    font-size: 0.72rem;
}
.field__input,
.field__textarea {
    padding: 10px 14px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.88rem;
    background: #fafafa;
    color: #000;
    width: 100%;
    box-sizing: border-box;
    font-family: inherit;
    transition: border-color 0.15s, background-color 0.15s, box-shadow 0.15s;
}
.field__input:hover,
.field__textarea:hover {
    border-color: #bbb;
}
.field__input:focus,
.field__textarea:focus {
    outline: none;
    border-color: #000;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(0,0,0,0.06);
}
.field__textarea {
    min-height: 100px;
    resize: vertical;
    line-height: 1.6;
}
.field__textarea--code {
    font-family: 'SF Mono', Menlo, Monaco, monospace;
    font-size: 0.82rem;
    min-height: 120px;
    background: #1a1a1a;
    color: #e0e0e0;
    border-color: #333;
}
.field__textarea--code:focus {
    background: #111;
    border-color: #555;
    box-shadow: 0 0 0 3px rgba(255,255,255,0.05);
}
.field__textarea--code:hover {
    border-color: #555;
}
.field__note {
    font-size: 0.72rem;
    color: #999;
    line-height: 1.4;
}

/* Toggle switch */
.toggle-field {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 0;
    border-bottom: 1px solid #f5f5f5;
}
.toggle-field:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.toggle-field__info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.toggle-field__label {
    font-size: 0.88rem;
    font-weight: 600;
    color: #000;
}
.toggle-field__desc {
    font-size: 0.78rem;
    color: #888;
}
.toggle-switch {
    position: relative;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}
.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}
.toggle-switch__slider {
    position: absolute;
    inset: 0;
    background-color: #ddd;
    border-radius: 24px;
    cursor: pointer;
    transition: background-color 0.2s;
}
.toggle-switch__slider::before {
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
.toggle-switch input:checked + .toggle-switch__slider {
    background-color: #000;
}
.toggle-switch input:checked + .toggle-switch__slider::before {
    transform: translateX(20px);
}
.toggle-switch input:focus-visible + .toggle-switch__slider {
    box-shadow: 0 0 0 3px rgba(0,0,0,0.15);
}

/* Social input with icon */
.social-input {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
    background: #fafafa;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.social-input:hover { border-color: #bbb; }
.social-input:focus-within {
    border-color: #000;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(0,0,0,0.06);
}
.social-input__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    flex-shrink: 0;
    background: #f0f0f0;
    color: #666;
}
.social-input__field {
    flex: 1;
    border: none;
    padding: 10px 12px;
    font-size: 0.88rem;
    background: transparent;
    color: #000;
    font-family: inherit;
    outline: none;
    width: 100%;
}

/* ─── Form Actions ──────────────────────────────────────────── */
.settings-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e5e5;
    margin-top: 0.5rem;
}
.settings-actions__left {
    font-size: 0.78rem;
    color: #aaa;
}
.settings-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 24px;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    border: 1px solid #000;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.15s ease;
    font-family: inherit;
    background: #000;
    color: #fff;
}
.settings-btn:hover {
    background: #222;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.settings-btn:active {
    transform: translateY(0);
}

/* ─── Responsive ────────────────────────────────────────────── */
@media (max-width: 768px) {
    .settings-card { padding: 1.25rem; }
    .field-grid { grid-template-columns: 1fr; }
    .settings-tabs { gap: 0; }
    .settings-tab { padding: 0.7rem 0.9rem; font-size: 0.75rem; }
    .settings-header { flex-direction: column; gap: 0.5rem; }
    .settings-actions { flex-direction: column; gap: 1rem; align-items: flex-end; }
}
</style>

<?php
    $tabConfig = [
        'general'  => [
            'label' => 'General',
            'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
        ],
        'social'   => [
            'label' => 'Social',
            'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>',
        ],
        'seo'      => [
            'label' => 'SEO',
            'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
        ],
        'advanced' => [
            'label' => 'Advanced',
            'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
        ],
        'security' => [
            'label' => 'Security',
            'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
        ],
    ];

    $groupedSettings = [];
    foreach ($grouped as $groupKey => $groupItems) {
        $groupedSettings[$groupKey] = $groupItems;
    }

    $activeTab = $activeTab ?? 'general';
    if (!isset($tabConfig[$activeTab])) {
        $activeTab = 'general';
    }

    $fieldDescs = [
        'site_name'           => 'The name displayed in the header, title tag, and throughout the site',
        'site_tagline'        => 'A short description of your site, often used in the header or SEO',
        'site_logo'           => 'Full URL to your logo image (e.g. https://site.com/logo.png)',
        'site_favicon'        => 'Full URL to your favicon (e.g. https://site.com/favicon.ico)',
        'posts_per_page'      => 'Number of posts displayed per page on listing pages',
        'comments_enabled'    => 'Allow visitors to leave comments on your posts',
        'comments_moderation' => 'Hold new comments for review before publishing them',
        'social_twitter'      => 'Your Twitter/X profile URL',
        'social_facebook'     => 'Your Facebook page or profile URL',
        'social_linkedin'     => 'Your LinkedIn profile or company page URL',
        'social_github'       => 'Your GitHub profile URL',
        'robots_txt'          => 'Controls how search engines crawl your site. Use {APP_URL} as a placeholder.',
        'analytics_code'      => 'Paste your Google Analytics, Plausible, or other tracking script here',
        'custom_header_code'  => 'HTML/JS injected before the closing &lt;/head&gt; tag on every page',
        'custom_footer_code'  => 'HTML/JS injected before the closing &lt;/body&gt; tag on every page',
        'recaptcha_enabled'   => 'Protect forms from spam with Google reCAPTCHA v3',
        'recaptcha_site_key'  => 'The public site key from your Google reCAPTCHA admin console',
        'recaptcha_secret_key'=> 'The secret key — never expose this publicly',
        'recaptcha_min_score' => 'Score between 0.0 and 1.0 — lower values are more lenient (default: 0.5)',
    ];

    $socialPlatforms = [
        'social_twitter'  => ['label' => 'Twitter / X', 'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'],
        'social_facebook' => ['label' => 'Facebook', 'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>'],
        'social_linkedin' => ['label' => 'LinkedIn', 'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>'],
        'social_github'   => ['label' => 'GitHub', 'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>'],
    ];
?>

<div class="settings-page">

    <!-- Header -->
    <div class="settings-header">
        <div class="settings-header__left">
            <h1><?= h($pageTitle) ?></h1>
            <p class="settings-header__desc">Manage your website configuration, social links, SEO, and advanced options.</p>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="settings-tabs" role="tablist">
        <?php foreach ($tabConfig as $tabKey => $tabInfo): ?>
            <?php if (!isset($groupedSettings[$tabKey]) && !in_array($tabKey, ['general', 'security'])) continue; ?>
            <a class="settings-tab<?= $activeTab === $tabKey ? ' active' : '' ?>"
               role="tab"
               data-tab="<?= h($tabKey) ?>"
               onclick="switchTab('<?= h($tabKey) ?>');">
                <?= $tabInfo['icon'] ?>
                <?= h($tabInfo['label']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <form method="POST" action="<?= url('admin/settings/save') ?>">
        <?= \App\Core\Csrf::field() ?>
        <input type="hidden" name="active_tab" id="active_tab" value="<?= h($activeTab) ?>">

        <!-- ═══════════ GENERAL TAB ═══════════ -->
        <div class="settings-panel<?= $activeTab === 'general' ? ' active' : '' ?>" data-panel="general">

            <!-- Site Identity Card -->
            <div class="settings-card">
                <div class="settings-card__header">
                    <div class="settings-card__icon settings-card__icon--general">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    </div>
                    <div>
                        <h3 class="settings-card__title">Site Identity</h3>
                        <p class="settings-card__subtitle">Your website's name, tagline, and branding</p>
                    </div>
                </div>
                <div class="field-grid">
                    <?php
                        $generalKeys = ['site_name', 'site_tagline', 'site_logo', 'site_favicon', 'posts_per_page'];
                        $generalSettings = $groupedSettings['general'] ?? [];
                        foreach ($generalSettings as $s):
                            if (!in_array($s['key_name'], $generalKeys)) continue;
                            $key = $s['key_name'];
                            $val = $s['value'] ?? '';
                            $lbl = $s['label'] ?? ucwords(str_replace('_', ' ', $key));
                            $desc = $fieldDescs[$key] ?? '';
                            $fullWidth = in_array($key, ['site_tagline']);
                    ?>
                        <div class="field<?= $fullWidth ? ' field--full' : '' ?>">
                            <label class="field__label" for="s-<?= h($key) ?>">
                                <?= h($lbl) ?>
                                <?php if ($key === 'posts_per_page'): ?>
                                    <span class="field__label-hint">numeric</span>
                                <?php endif; ?>
                            </label>
                            <input type="text" class="field__input" id="s-<?= h($key) ?>" name="settings[<?= h($key) ?>]" value="<?= h($val) ?>" placeholder="<?= h($desc) ?>">
                            <?php if ($desc): ?><span class="field__note"><?= h($desc) ?></span><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Comments Card -->
            <div class="settings-card">
                <div class="settings-card__header">
                    <div class="settings-card__icon settings-card__icon--general">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="settings-card__title">Comments</h3>
                        <p class="settings-card__subtitle">Control how comments behave on your site</p>
                    </div>
                </div>

                <?php
                    $boolKeys = ['comments_enabled', 'comments_moderation'];
                    $boolDescs = [
                        'comments_enabled'    => 'Allow visitors to leave comments on your posts',
                        'comments_moderation' => 'Hold new comments for review before they appear publicly',
                    ];
                    foreach ($generalSettings as $s):
                        if (!in_array($s['key_name'], $boolKeys)) continue;
                        $key = $s['key_name'];
                        $val = $s['value'] ?? '';
                        $lbl = $s['label'] ?? ucwords(str_replace('_', ' ', $key));
                ?>
                    <div class="toggle-field">
                        <div class="toggle-field__info">
                            <span class="toggle-field__label"><?= h($lbl) ?></span>
                            <span class="toggle-field__desc"><?= h($boolDescs[$key] ?? '') ?></span>
                        </div>
                        <label class="toggle-switch">
                            <input type="hidden" name="settings[<?= h($key) ?>]" value="0">
                            <input type="checkbox" name="settings[<?= h($key) ?>]" value="1" <?= $val ? 'checked' : '' ?>>
                            <span class="toggle-switch__slider"></span>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ═══════════ SOCIAL TAB ═══════════ -->
        <div class="settings-panel<?= $activeTab === 'social' ? ' active' : '' ?>" data-panel="social">
            <div class="settings-card">
                <div class="settings-card__header">
                    <div class="settings-card__icon settings-card__icon--social">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                    </div>
                    <div>
                        <h3 class="settings-card__title">Social Media Profiles</h3>
                        <p class="settings-card__subtitle">Connect your social accounts — these links appear in the site header and footer</p>
                    </div>
                </div>

                <div class="field-grid">
                    <?php foreach ($socialPlatforms as $sKey => $platform):
                        $sVal = '';
                        foreach (($groupedSettings['social'] ?? []) as $ss) {
                            if ($ss['key_name'] === $sKey) { $sVal = $ss['value'] ?? ''; break; }
                        }
                    ?>
                        <div class="field">
                            <label class="field__label" for="s-<?= h($sKey) ?>"><?= h($platform['label']) ?></label>
                            <div class="social-input">
                                <span class="social-input__icon"><?= $platform['icon'] ?></span>
                                <input type="url" class="social-input__field" id="s-<?= h($sKey) ?>" name="settings[<?= h($sKey) ?>]" value="<?= h($sVal) ?>" placeholder="https://">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ═══════════ SEO TAB ═══════════ -->
        <div class="settings-panel<?= $activeTab === 'seo' ? ' active' : '' ?>" data-panel="seo">
            <div class="settings-card">
                <div class="settings-card__header">
                    <div class="settings-card__icon settings-card__icon--seo">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    <div>
                        <h3 class="settings-card__title">Search Engine Optimization</h3>
                        <p class="settings-card__subtitle">Control how search engines discover and crawl your site</p>
                    </div>
                </div>

                <div class="field-grid">
                    <?php foreach (($groupedSettings['seo'] ?? []) as $s):
                        $key = $s['key_name'];
                        $val = $s['value'] ?? '';
                        $lbl = $s['label'] ?? ucwords(str_replace('_', ' ', $key));
                        $desc = $fieldDescs[$key] ?? '';
                    ?>
                        <div class="field field--full">
                            <label class="field__label" for="s-<?= h($key) ?>"><?= h($lbl) ?></label>
                            <?php if (($s['type'] ?? '') === 'text' || mb_strlen($val) > 100): ?>
                                <textarea class="field__textarea" id="s-<?= h($key) ?>" name="settings[<?= h($key) ?>]" rows="6"><?= h($val) ?></textarea>
                            <?php else: ?>
                                <input type="text" class="field__input" id="s-<?= h($key) ?>" name="settings[<?= h($key) ?>]" value="<?= h($val) ?>">
                            <?php endif; ?>
                            <?php if ($desc): ?><span class="field__note"><?= h($desc) ?></span><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ═══════════ ADVANCED TAB ═══════════ -->
        <div class="settings-panel<?= $activeTab === 'advanced' ? ' active' : '' ?>" data-panel="advanced">
            <div class="settings-card">
                <div class="settings-card__header">
                    <div class="settings-card__icon settings-card__icon--advanced">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                    </div>
                    <div>
                        <h3 class="settings-card__title">Code Injection</h3>
                        <p class="settings-card__subtitle">Add custom scripts, analytics, and tracking codes</p>
                    </div>
                </div>

                <div class="field-grid">
                    <?php foreach (($groupedSettings['advanced'] ?? []) as $s):
                        $key = $s['key_name'];
                        $val = $s['value'] ?? '';
                        $lbl = $s['label'] ?? ucwords(str_replace('_', ' ', $key));
                        $desc = $fieldDescs[$key] ?? '';
                    ?>
                        <div class="field field--full">
                            <label class="field__label" for="s-<?= h($key) ?>"><?= h($lbl) ?></label>
                            <textarea class="field__textarea field__textarea--code" id="s-<?= h($key) ?>" name="settings[<?= h($key) ?>]" rows="6" spellcheck="false"><?= h($val) ?></textarea>
                            <?php if ($desc): ?><span class="field__note"><?= h($desc) ?></span><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ═══════════ SECURITY TAB ═══════════ -->
        <div class="settings-panel<?= $activeTab === 'security' ? ' active' : '' ?>" data-panel="security">
            <div class="settings-card">
                <div class="settings-card__header">
                    <div class="settings-card__icon settings-card__icon--security">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div>
                        <h3 class="settings-card__title">Google reCAPTCHA v3</h3>
                        <p class="settings-card__subtitle">Protect comment, subscribe, contact, and search forms from spam and abuse</p>
                    </div>
                </div>

                <?php
                    $securitySettings = $groupedSettings['security'] ?? [];
                    $securityMap = [];
                    foreach ($securitySettings as $ss) {
                        $securityMap[$ss['key_name']] = $ss['value'] ?? '';
                    }
                ?>

                <div class="toggle-field">
                    <div class="toggle-field__info">
                        <span class="toggle-field__label">Enable reCAPTCHA</span>
                        <span class="toggle-field__desc"><?= h($fieldDescs['recaptcha_enabled'] ?? '') ?></span>
                    </div>
                    <label class="toggle-switch">
                        <input type="hidden" name="settings[recaptcha_enabled]" value="0">
                        <input type="checkbox" name="settings[recaptcha_enabled]" value="1" <?= ($securityMap['recaptcha_enabled'] ?? '0') ? 'checked' : '' ?>>
                        <span class="toggle-switch__slider"></span>
                    </label>
                </div>

                <div class="field-grid" style="margin-top: 1.25rem;">
                    <div class="field">
                        <label class="field__label" for="s-recaptcha_site_key">Site Key</label>
                        <input type="text" class="field__input" id="s-recaptcha_site_key" name="settings[recaptcha_site_key]" value="<?= h($securityMap['recaptcha_site_key'] ?? '') ?>" placeholder="6Lc...">
                        <span class="field__note"><?= h($fieldDescs['recaptcha_site_key'] ?? '') ?></span>
                    </div>
                    <div class="field">
                        <label class="field__label" for="s-recaptcha_secret_key">Secret Key</label>
                        <input type="password" class="field__input" id="s-recaptcha_secret_key" name="settings[recaptcha_secret_key]" value="<?= h($securityMap['recaptcha_secret_key'] ?? '') ?>" placeholder="6Lc...">
                        <span class="field__note"><?= h($fieldDescs['recaptcha_secret_key'] ?? '') ?></span>
                    </div>
                    <div class="field">
                        <label class="field__label" for="s-recaptcha_min_score">
                            Minimum Score
                            <span class="field__label-hint">0.0 – 1.0</span>
                        </label>
                        <input type="number" class="field__input" id="s-recaptcha_min_score" name="settings[recaptcha_min_score]" value="<?= h($securityMap['recaptcha_min_score'] ?? '0.5') ?>" min="0" max="1" step="0.1" placeholder="0.5">
                        <span class="field__note"><?= h($fieldDescs['recaptcha_min_score'] ?? '') ?></span>
                    </div>
                </div>

                <div style="margin-top: 1.25rem; padding: 1rem; background: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb;">
                    <p style="font-size: 0.78rem; color: #666; margin: 0 0 0.5rem 0; font-weight: 600;">How to get your keys:</p>
                    <ol style="font-size: 0.75rem; color: #888; margin: 0; padding-left: 1.25rem; line-height: 1.8;">
                        <li>Go to <a href="https://www.google.com/recaptcha/admin" target="_blank" rel="noopener" style="color: #000; text-decoration: underline;">Google reCAPTCHA Admin Console</a></li>
                        <li>Click <strong>+</strong> to create a new site</li>
                        <li>Choose <strong>reCAPTCHA v3</strong> and add your domain(s)</li>
                        <li>Copy the <strong>Site Key</strong> and <strong>Secret Key</strong> here</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Save Actions -->
        <div class="settings-actions">
            <span class="settings-actions__left">Changes are saved immediately when you click Save.</span>
            <button type="submit" class="settings-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save Settings
            </button>
        </div>
    </form>
</div>

<script>
function switchTab(tabName) {
    document.querySelectorAll('.settings-tab').forEach(function(t) { t.classList.remove('active'); });
    document.querySelector('.settings-tab[data-tab="' + tabName + '"]').classList.add('active');

    document.querySelectorAll('.settings-panel').forEach(function(p) { p.classList.remove('active'); });
    document.querySelector('.settings-panel[data-panel="' + tabName + '"]').classList.add('active');

    document.getElementById('active_tab').value = tabName;

    var url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    history.replaceState(null, '', url);
}
</script>
