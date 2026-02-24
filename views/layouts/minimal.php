<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title ?? 'Login') ?></title>
    <link rel="stylesheet" href="<?= url('assets/css/admin.css') ?>">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body.minimal-layout {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            color: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .minimal-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .minimal-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 40px 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body class="minimal-layout">

    <div class="minimal-container">
        <div class="minimal-card">
            <?= $content ?>
        </div>
    </div>

</body>
</html>
