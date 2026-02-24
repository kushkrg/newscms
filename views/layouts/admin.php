<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h(($title ?? 'Dashboard') . ' - Admin') ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>">
</head>
<body class="admin-layout">

    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?= url('/') ?>" class="sidebar-logo">
                <span class="logo-text">CMS</span>
            </a>
        </div>

        <nav class="sidebar-nav">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="<?= url('admin') ?>" class="nav-link<?= ($currentPage ?? '') === 'dashboard' ? ' active' : '' ?>">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin/posts') ?>" class="nav-link<?= ($currentPage ?? '') === 'posts' ? ' active' : '' ?>">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                        <span>Posts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin/pages') ?>" class="nav-link<?= ($currentPage ?? '') === 'pages' ? ' active' : '' ?>">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        <span>Pages</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin/categories') ?>" class="nav-link<?= ($currentPage ?? '') === 'categories' ? ' active' : '' ?>">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin/tags') ?>" class="nav-link<?= ($currentPage ?? '') === 'tags' ? ' active' : '' ?>">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                        <span>Tags</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin/comments') ?>" class="nav-link<?= ($currentPage ?? '') === 'comments' ? ' active' : '' ?>">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>Comments</span>
                        <?php
                        $pendingCount = \App\Models\Comment::pendingCount();
                        if ($pendingCount > 0): ?>
                            <span class="nav-badge"><?= (int) $pendingCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin/media') ?>" class="nav-link<?= ($currentPage ?? '') === 'media' ? ' active' : '' ?>">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        <span>Media</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin/subscribers') ?>" class="nav-link<?= ($currentPage ?? '') === 'subscribers' ? ' active' : '' ?>">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <span>Subscribers</span>
                        <?php
                        $subCount = \App\Models\Subscriber::activeCount();
                        if ($subCount > 0): ?>
                            <span class="nav-badge"><?= (int) $subCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <?php if (\App\Core\Auth::hasRole('super_admin')): ?>
                <li class="nav-item">
                    <a href="<?= url('admin/users') ?>" class="nav-link<?= ($currentPage ?? '') === 'users' ? ' active' : '' ?>">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= url('admin/settings') ?>" class="nav-link<?= ($currentPage ?? '') === 'settings' ? ' active' : '' ?>">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        <span>Settings</span>
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a href="<?= url('admin/redirects') ?>" class="nav-link<?= ($currentPage ?? '') === 'redirects' ? ' active' : '' ?>">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 14 20 9 15 4"></polyline>
                            <path d="M4 20v-7a4 4 0 0 1 4-4h12"></path>
                        </svg>
                        <span>Redirects</span>
                    </a>
                </li>
                <?php if (\App\Core\Auth::hasRole('super_admin')): ?>
                <li class="nav-item">
                    <a href="<?= url('admin/ads') ?>" class="nav-link<?= ($currentPage ?? '') === 'ads' ? ' active' : '' ?>">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        <span>Ads</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="user-avatar">
                    <?= strtoupper(mb_substr(h(\App\Core\Session::get('user_name', 'U')), 0, 1)) ?>
                </div>
                <div class="user-info">
                    <span class="user-name"><?= h(\App\Core\Session::get('user_name', 'User')) ?></span>
                    <span class="user-role"><?= h(ucfirst(str_replace('_', ' ', \App\Core\Session::get('user_role', '')))) ?></span>
                </div>
            </div>
            <form method="POST" action="<?= url('admin/logout') ?>" class="logout-form">
                <?= \App\Core\Csrf::field() ?>
                <button type="submit" class="logout-btn" title="Sign out">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">

        <!-- Top Header Bar -->
        <header class="content-header">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <h1 class="page-title"><?= h($pageTitle ?? $title ?? 'Dashboard') ?></h1>
            </div>
            <div class="header-right">
                <span class="header-user"><?= h(\App\Core\Session::get('user_name', '')) ?></span>
            </div>
        </header>

        <!-- Flash Messages -->
        <?php $flashSuccess = \App\Core\Session::getFlash('success'); ?>
        <?php $flashError = \App\Core\Session::getFlash('error'); ?>

        <?php if ($flashSuccess): ?>
        <div class="flash-message flash-success">
            <span class="flash-text"><?= h($flashSuccess) ?></span>
            <button type="button" class="flash-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
        <?php endif; ?>

        <?php if ($flashError): ?>
        <div class="flash-message flash-error">
            <span class="flash-text"><?= h($flashError) ?></span>
            <button type="button" class="flash-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
        <?php endif; ?>

        <!-- Page Content -->
        <div class="content-body">
            <?= $content ?>
        </div>

    </main>

    <script src="<?= url('assets/js/admin.js') ?>"></script>
</body>
</html>
