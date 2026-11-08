<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$page_title = 'Dashboard';

$totalBooks = (int) $pdo->query(
    'SELECT COUNT(*) FROM books'
)->fetchColumn();

$totalUsers = (int) $pdo->query(
    "SELECT COUNT(*) FROM users WHERE role = 'user'"
)->fetchColumn();

$totalOrders = (int) $pdo->query(
    'SELECT COUNT(*) FROM orders'
)->fetchColumn();

$revenue = (float) $pdo->query(
    "
    SELECT COALESCE(SUM(total), 0)
    FROM orders
    WHERE status IN ('Diproses', 'Dikirim', 'Selesai')
    "
)->fetchColumn();

$recentOrders = $pdo->query(
    "
    SELECT
        o.id, o.order_number, o.total, o.status, o.created_at,
        o.customer_name, u.name AS user_name
    FROM orders o
    LEFT JOIN users u ON u.id = o.user_id
    ORDER BY o.created_at DESC, o.id DESC
    LIMIT 5
    "
)->fetchAll();

require __DIR__ . '/../includes/admin_header.php';
?>

<div class="stats-grid">
    <div class="stat-card">
        <div>
            <h4>TOTAL BUKU</h4>
            <div class="stat-number">
                <?= $totalBooks ?>
            </div>
            <p class="stat-sub">
                <?= $totalBooks ?> Tersedia
            </p>
        </div>

        <div class="stat-icon">📖</div>
    </div>

    <div class="stat-card">
        <div>
            <h4>TOTAL USER</h4>
            <div class="stat-number">
                <?= $totalUsers ?>
            </div>
            <p class="stat-sub">
                <?= $totalUsers ?> Aktif
            </p>
        </div>

        <div class="stat-icon">👥</div>
    </div>

    <div class="stat-card">
        <div>
            <h4>TOTAL PESANAN</h4>
            <div class="stat-number">
                <?= $totalOrders ?>
            </div>
            <p class="stat-sub">Semua pesanan</p>
        </div>

        <div class="stat-icon">📦</div>
    </div>

    <div class="stat-card">
        <div>
            <h4>TOTAL PENDAPATAN</h4>
            <div class="stat-number">
                <?= rupiah($revenue) ?>
            </div>
            <p class="stat-sub">Dari pesanan terkirim</p>
        </div>

        <div class="stat-icon">💰</div>
    </div>
</div>

<section class="admin-panel dashboard-orders-panel">
    <div class="toolbar dashboard-orders-toolbar">
        <div>
            <h2>Pesanan Terbaru</h2>
            <p>Ringkasan pesanan yang baru masuk ke toko.</p>
        </div>
        <a class="btn btn-outline" href="orders.php">Lihat Semua Pesanan</a>
    </div>

    <?php if (!$recentOrders): ?>
        <div class="dashboard-orders-empty">
            <span class="dashboard-orders-empty-icon">📦</span>
            <strong>Belum ada pesanan</strong>
            <span>Pesanan baru dari pelanggan akan muncul di sini.</span>
        </div>
    <?php else: ?>
        <div class="table-wrap dashboard-orders-table-wrap">
            <table class="data-table dashboard-orders-table">
                <thead>
                    <tr>
                        <th>Nomor Pesanan</th>
                        <th>Pemesan</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="dashboard-orders-action-heading">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>
                                <strong class="dashboard-order-number">
                                    <?= e($order['order_number']) ?>
                                </strong>
                            </td>
                            <td><?= e($order['user_name'] ?: $order['customer_name']) ?></td>
                            <td><strong class="dashboard-order-total-text"><?= rupiah($order['total']) ?></strong></td>
                            <td><?= e(tanggal_indonesia($order['created_at'])) ?></td>
                            <td>
                                <span class="dashboard-order-status dashboard-order-status-<?= e(strtolower($order['status'])) ?>">
                                    <?= e($order['status']) ?>
                                </span>
                            </td>
                            <td class="dashboard-orders-action-cell">
                                <a class="dashboard-order-detail-link" href="orders.php">Lihat</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
