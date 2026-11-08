<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

require_user();

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare(
    'SELECT * FROM orders WHERE id = ? AND user_id = ?'
);

$stmt->execute([
    $id,
    user()['id'],
]);

$order = $stmt->fetch();

if (!$order) {
    redirect('index.php');
}

$page_title = 'Pesanan Berhasil - RAStory Bookstore';

require __DIR__ . '/includes/header.php';
?>

<div class="page-wrap">
    <div class="panel">
        <h1>Pesanan berhasil dibuat 🎉</h1>

        <p>
            Nomor pesanan:
            <strong><?= e($order['order_number']) ?></strong>
        </p>

        <p>
            Status:
            <span class="badge">
                <?= e($order['status']) ?>
            </span>
        </p>

        <p>
            Metode pembayaran:
            <strong><?= $order['payment_method'] === 'BANK' ? 'Transfer Bank' : 'Bayar di Tempat (COD)' ?></strong>
        </p>

        <?php if ($order['payment_method'] === 'BANK'): ?>
            <div class="notice">
                Bukti transfer sudah diterima. Pesanan kamu sedang menunggu pemeriksaan admin.
            </div>
        <?php endif; ?>

        <p>
            Total:
            <strong><?= rupiah($order['total']) ?></strong>
        </p>

        <a
            class="btn btn-primary"
            href="index.php"
        >
            Kembali ke Beranda
        </a>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
