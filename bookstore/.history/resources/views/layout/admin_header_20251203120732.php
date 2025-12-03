<?php /** @var string $title */ ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?= rtrim($baseUrl, '/') ?>/">
    <title><?= htmlspecialchars(($title ?? 'Admin') . ' - ' . $appName . ' Admin') ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>css/styles.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-body">
<div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <a href="<?= $baseUrl ?>admin" class="sidebar-logo">
                <i class="fas fa-book"></i>
                <span><?= htmlspecialchars($appName) ?> Admin</span>
            </a>
            <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <ul class="nav-list">
                <li>
                    <a href="<?= $baseUrl ?>admin" class="nav-item <?= (empty($_GET['page']) || $_GET['page'] === 'dashboard') ? 'active' : '' ?>">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $baseUrl ?>admin?page=products" class="nav-item <?= ($_GET['page'] ?? '') === 'products' ? 'active' : '' ?>">
                        <i class="fas fa-book"></i>
                        <span>Quản lý sản phẩm</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $baseUrl ?>admin?page=orders" class="nav-item <?= ($_GET['page'] ?? '') === 'orders' ? 'active' : '' ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Quản lý đơn hàng</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $baseUrl ?>admin?page=users" class="nav-item <?= ($_GET['page'] ?? '') === 'users' ? 'active' : '' ?>">
                        <i class="fas fa-users"></i>
                        <span>Quản lý người dùng</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $baseUrl ?>admin?page=vouchers" class="nav-item <?= ($_GET['page'] ?? '') === 'vouchers' ? 'active' : '' ?>">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Quản lý Voucher</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $baseUrl ?>admin?page=categories" class="nav-item <?= ($_GET['page'] ?? '') === 'categories' ? 'active' : '' ?>">
                        <i class="fas fa-tags"></i>
                        <span>Quản lý Danh mục</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $baseUrl ?>admin?page=statistics" class="nav-item <?= ($_GET['page'] ?? '') === 'statistics' ? 'active' : '' ?>">
                        <i class="fas fa-chart-bar"></i>
                        <span>Thống kê doanh thu</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= $baseUrl ?>" class="nav-item" target="_blank">
                <i class="fas fa-external-link-alt"></i>
                <span>Về trang chủ</span>
            </a>
            <a href="<?= $baseUrl ?>logout" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Đăng xuất</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="admin-main">
        <!-- Topbar -->
        <header class="admin-topbar">
            <div class="topbar-content">
                <button class="topbar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-title">
                    <h1><?= htmlspecialchars($title ?? 'Dashboard') ?></h1>
                </div>
                <div class="topbar-actions">
                    <?php 
                    if (session_status() !== PHP_SESSION_ACTIVE) {
                        session_start();
                    }
                    use App\Helpers\AdminHelper;
                    $adminInfo = AdminHelper::getCurrentAdmin();
                    if ($adminInfo): ?>
                        <div class="user-dropdown">
                            <button class="user-dropdown-toggle" onclick="toggleUserDropdown()">
                                <div class="user-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <span class="user-name"><?= htmlspecialchars($adminInfo['name']) ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="user-dropdown-menu" id="userDropdownMenu">
                                <div class="user-dropdown-header">
                                    <div class="user-avatar-large">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name-bold"><?= htmlspecialchars($adminInfo['name']) ?></div>
                                        <div class="user-email"><?= htmlspecialchars($adminInfo['email']) ?></div>
                                        <div class="user-role">
                                            <span class="badge badge-success"><?= htmlspecialchars($adminInfo['role']) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="user-dropdown-divider"></div>
                                <a href="<?= $baseUrl ?>admin?page=users" class="user-dropdown-item">
                                    <i class="fas fa-users"></i>
                                    <span>Quản lý người dùng</span>
                                </a>
                                <a href="<?= $baseUrl ?>" class="user-dropdown-item" target="_blank">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>Về trang chủ</span>
                                </a>
                                <div class="user-dropdown-divider"></div>
                                <a href="<?= $baseUrl ?>logout" class="user-dropdown-item user-dropdown-item-danger">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Đăng xuất</span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="admin-content">
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

