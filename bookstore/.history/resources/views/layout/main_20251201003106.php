<?php /** @var string $title */ ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= rtrim($baseUrl, '/') ?>/">
    <title><?= htmlspecialchars(($title ?? APP_NAME) . ' - ' . APP_NAME) ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= $baseUrl ?>css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="<?= $baseUrl ?>js/banner-slider.js" defer></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <?= $content ?? '' ?>
    </main>

    <?php include 'footer.php'; ?>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>