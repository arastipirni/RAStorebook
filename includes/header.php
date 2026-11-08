<?php
require_once __DIR__ . '/functions.php';

// Menghitung jumlah buku yang valid untuk badge keranjang.
$jumlahKeranjang = (isset($pdo) && $pdo instanceof PDO)
    ? cart_count($pdo)
    : 0;
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <title><?= e($page_title ?? 'BookStore') ?></title>
    <link rel="icon" href="../assets/images/depan_logo.png">
    <link rel="stylesheet" href="assets/css/style.css">
    

    <?php if (basename($_SERVER['PHP_SELF']) === 'profile.php'): ?>
        <link
            rel="stylesheet"
            href="assets/css/profile.css">
    <?php endif; ?>
</head>
<body>

<header class="site-header">
    <a class="brand" href="index.php" aria-label="Beranda RAStorebook">
        <img
            src="assets/images/rastorebook.png"
            alt="RAStorebook"
        >
    </a>

    <?php $navPage = basename($_SERVER['PHP_SELF']); ?>
    <nav aria-label="Navigasi utama">
        <a class="<?= $navPage === 'index.php' ? 'active' : '' ?>" href="index.php">Beranda</a>
        <a class="<?= $navPage === 'about.php' ? 'active' : '' ?>" href="about.php">Tentang Kami</a>
        <a class="<?= $navPage === 'contact.php' ? 'active' : '' ?>" href="contact.php">Kontak</a>
    </nav>

    <a
        class="header-cart"
        href="cart.php"
        aria-label="Keranjang"
    >
        🛒
        <span class="cart-badge">
        <?= $jumlahKeranjang ?>
    </span>
    </a>

    <div class="header-actions">
        <?php if (is_admin_preview()): ?>
            <!-- Admin sedang melihat website dengan tampilan yang sama seperti pelanggan. -->
            <span class="header-user">Hi, Admin</span>

            <a class="btn btn-outline admin-preview-panel-btn" href="admin/index.php">
                Panel Admin
            </a>

            <a class="btn btn-primary" href="admin/logout.php">
                Keluar
            </a>
        <?php elseif (is_user()): ?>
            <a
                class="header-user profile-link"
                href="profile.php"
                title="Buka Profil"
            >
                Hi, <?= e(user()['name']) ?>
            </a>

            <a class="btn btn-outline" href="logout.php">
                Keluar
            </a>
        <?php else: ?>
            <a class="btn btn-outline" href="register.php">
                Daftar
            </a>

            <a class="btn btn-primary" href="login.php">
                Masuk
            </a>
        <?php endif; ?>
    </div>
</header>

<?php if ($f = flash()): ?>
    <div class="flash <?= e($f['type']) ?>">
        <?= e($f['message']) ?>
    </div>
<?php endif; ?>

<main>
