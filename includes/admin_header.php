<?php
require_once __DIR__ . '/functions.php';
require_admin();

// Pesanan berstatus Menunggu dianggap sebagai pesanan baru yang perlu diperiksa admin.
$newOrdersCount = (int) $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Menunggu'")->fetchColumn();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <title><?= e($page_title ?? 'Dashboard') ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/images/depan_logo.png">
</head>
<body>

<div class="admin-shell">

    <aside class="admin-sidebar">
        <img
            src="../assets/images/rastorebook.png"
            class="admin-logo"
            alt="RAStorebook"
        >

        <h2>Panel Admin</h2>

        <nav class="admin-nav" aria-label="Menu admin">
            <?php $current = basename($_SERVER['PHP_SELF']); ?>

            <a
                class="<?= $current === 'index.php' ? 'active' : '' ?>"
                href="index.php"
            >
                Dashboard
            </a>

            <a
                class="<?= $current === 'books.php' ? 'active' : '' ?>"
                href="books.php"
            >
                Kelola Buku
            </a>

            <a
                class="<?= $current === 'categories.php' ? 'active' : '' ?>"
                href="categories.php"
            >
                Kategori
            </a>

            <a
                class="<?= $current === 'users.php' ? 'active' : '' ?>"
                href="users.php"
            >
                Daftar Pengguna
            </a>

            <a
                class="<?= $current === 'orders.php' ? 'active' : '' ?>"
                href="orders.php"
                <?= $newOrdersCount > 0 ? 'aria-label="Pesanan, ' . $newOrdersCount . ' pesanan menunggu" title="' . $newOrdersCount . ' pesanan menunggu"' : '' ?>
            >
                Pesanan
                <?php if ($newOrdersCount > 0): ?>
                    <span class="admin-nav-dot" aria-hidden="true"></span>
                <?php endif; ?>
            </a>

            <a class="<?= $current === 'contact.php' ? 'active' : '' ?>" href="contact.php">Kontak</a>

            <a class="admin-view-link <?= $current === 'website.php' ? 'active' : '' ?>" href="website.php">Lihat Website</a>
        </nav>

        <div class="admin-account">
            <div class="admin-avatar">●</div>

            <div>
                <strong><?= e(admin_user()['name'] ?? 'Admin RAStorebook') ?></strong>

                <a href="logout.php">
                    ⇥ &nbsp;Keluar
                </a>
            </div>
        </div>
    </aside>

    <section class="admin-main">
        <header class="admin-topbar">
            <div>
                <h1><?= e($page_title ?? 'Dasbor') ?></h1>
                <p>Kelola toko buku kamu</p>
            </div>

            <div class="admin-date">
                <?= e(tanggal_indonesia('now')) ?>
            </div>
        </header>

        <?php if ($f = flash()): ?>
            <div class="flash <?= e($f['type']) ?>">
                <?= e($f['message']) ?>
            </div>
        <?php endif; ?>

        <div class="admin-content">
