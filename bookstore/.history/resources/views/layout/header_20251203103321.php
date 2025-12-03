<?php /** @var string $title */ ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= rtrim($baseUrl, '/') ?>/">
    <title><?= htmlspecialchars(($title ?? APP_NAME) . ' - ' . APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="<?= $baseUrl ?>js/banner-slider.js" defer></script>
</head>
<body>
<header class="site-header">
    <!-- Main Header -->
    <div class="header-main">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="header-logo">
                    <a href="<?= $baseUrl ?>" class="brand">
                        <i class="fas fa-book"></i>
                        <?= htmlspecialchars(APP_NAME) ?>
                    </a>
                </div>
                
                <!-- Search Bar -->
                <div class="header-search">
                    <form class="search-form" action="<?= rtrim($baseUrl, '/') ?>/search" method="GET">
                        <input type="text" name="q" placeholder="Tìm kiếm sách, tác giả..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                
                <!-- Cart & Account -->
                <div class="header-actions">
                    <a href="<?= rtrim($baseUrl, '/') ?>/cart" class="cart-link" id="cart-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count" id="cart-count"><?php
                            if (session_status() !== PHP_SESSION_ACTIVE) {
                                session_start();
                            }
                            $cart = $_SESSION['cart'] ?? [];
                            $cartQty = 0;
                            foreach ($cart as $qty) {
                                $cartQty += (int)$qty;
                            }
                            echo $cartQty;
                        ?></span>
                        <span>Giỏ hàng</span>
                    </a>
                    <?php 
                    if (session_status() !== PHP_SESSION_ACTIVE) {
                        session_start();
                    }
                    if (isset($_SESSION['user'])): 
                        $user = $_SESSION['user'];
                        $userName = htmlspecialchars($user['ho_ten'] ?? $user['name'] ?? $user['ten_dang_nhap'] ?? 'Người dùng');
                        $userEmail = htmlspecialchars($user['email'] ?? '');
                    ?>
                        <div class="user-dropdown">
                            <button class="user-dropdown-toggle" onclick="toggleUserDropdown()">
                                <div class="user-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <span class="user-name"><?= $userName ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="user-dropdown-menu" id="userDropdownMenu">
                                <div class="user-dropdown-header">
                                    <div class="user-avatar-large">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name-bold"><?= $userName ?></div>
                                        <div class="user-email"><?= $userEmail ?></div>
                                    </div>
                                </div>
                                <div class="user-dropdown-divider"></div>
                                <a href="<?= rtrim($baseUrl, '/') ?>/account" class="user-dropdown-item">
                                    <i class="fas fa-user"></i>
                                    <span>Tài khoản của tôi</span>
                                </a>
                                <a href="<?= rtrim($baseUrl, '/') ?>/orders" class="user-dropdown-item">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span>Đơn hàng của tôi</span>
                                </a>
                                <div class="user-dropdown-divider"></div>
                                <a href="<?= rtrim($baseUrl, '/') ?>/logout" class="user-dropdown-item user-dropdown-item-danger">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Đăng xuất</span>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= rtrim($baseUrl, '/') ?>/login" class="account-link">
                            <i class="fas fa-user-circle"></i>
                            <span>Đăng nhập</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="main-navigation">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="<?= $baseUrl ?>" class="active">Trang chủ</a></li>
                <li class="dropdown categories-dropdown">
                    <a href="<?= rtrim($baseUrl, '/') ?>/books">Danh mục sách <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-heading">Theo chủ đề</li>
                        <li><a href="<?= rtrim($baseUrl, '/') ?>/books?category=van-hoc">Văn học</a></li>
                        <li><a href="<?= rtrim($baseUrl, '/') ?>/books?category=kinh-te">Kinh tế</a></li>
                        <li><a href="<?= rtrim($baseUrl, '/') ?>/books?category=ky-nang">Kỹ năng sống</a></li>
                        <li><a href="<?= rtrim($baseUrl, '/') ?>/books?category=tam-ly">Tâm lý - Học đường</a></li>
                        <li class="dropdown-heading">Theo ngôn ngữ</li>
                        <li><a href="<?= rtrim($baseUrl, '/') ?>/books?category=english">English Books</a></li>
                        <li><a href="<?= rtrim($baseUrl, '/') ?>/books?category=japanese">Japanese Books</a></li>
                        <li><a href="<?= rtrim($baseUrl, '/') ?>/books?category=korean">Korean Books</a></li>
                        <li><a href="<?= rtrim($baseUrl, '/') ?>/books?category=chinese">Chinese Books</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="<?= $baseUrl ?>index.php?page=books&category=quoc-van">Sách quốc văn <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= $baseUrl ?>index.php?page=books&category=van-hoc">Văn học</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books&category=kinh-te">Kinh tế</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books&category=ky-nang">Kỹ năng</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books&category=tieu-thuyet">Tiểu thuyết</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="<?= $baseUrl ?>index.php?page=books&category=ngoai-van">Sách ngoại văn <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= $baseUrl ?>index.php?page=books&category=english">English</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books&category=japanese">Japanese</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books&category=korean">Korean</a></li>
                        <li><a href="<?= $baseUrl ?>index.php?page=books&category=chinese">Chinese</a></li>
                    </ul>
                </li>
                <li><a href="<?= $baseUrl ?>office-supplies">Văn phòng phẩm</a></li>
                <li><a href="<?= $baseUrl ?>gifts">Quà tặng</a></li>
                <li><a href="<?= $baseUrl ?>promotions">Khuyến mãi</a></li>
                <li><a href="<?= $baseUrl ?>news">Tin tức – sự kiện</a></li>
            </ul>
        </div>
    </nav>
</header>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Toast Notification Container -->
<div id="toast-container" class="toast-container"></div>

<main>

