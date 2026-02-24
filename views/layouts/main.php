<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php if (isset($seo) && $seo instanceof \App\Core\SEO): ?>
        <?= $seo->renderHead() ?>
    <?php else: ?>
        <title><?= h($title ?? \App\Models\Setting::get('site_name', 'NewsCMS')) ?></title>
    <?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= url('assets/css/main.css') ?>">
</head>
<body>

    <?php include VIEW_PATH . '/partials/header.php'; ?>

    <?= \App\Core\Ad::render('header_banner') ?>

    <main class="site-main">
        <?php include VIEW_PATH . '/partials/flash-message.php'; ?>
        <?= $content ?>
    </main>

    <?php include VIEW_PATH . '/partials/footer.php'; ?>

    <script src="<?= url('assets/js/main.js') ?>"></script>

    <?= \App\Core\Recaptcha::script() ?>

    <?php
    $analyticsCode = \App\Models\Setting::get('analytics_code');
    if ($analyticsCode): ?>
        <?= $analyticsCode ?>
    <?php endif; ?>

    <?php
    $customFooterCode = \App\Models\Setting::get('custom_footer_code');
    if ($customFooterCode): ?>
        <?= $customFooterCode ?>
    <?php endif; ?>

</body>
</html>
