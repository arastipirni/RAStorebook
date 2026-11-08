<?php

/*
|--------------------------------------------------------------------------
| PROFILE.PHP
|--------------------------------------------------------------------------
| Kegunaan file:
| 1. Menampilkan profil User yang sedang login.
| 2. Menampilkan nama dan email User.
| 3. Menampilkan riwayat pembelian buku.
| 4. Menampilkan detail setiap pesanan dan buku yang dibeli.
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';


/*
|--------------------------------------------------------------------------
| Halaman ini hanya boleh dibuka oleh User yang sudah login.
|--------------------------------------------------------------------------
*/

require_user();


/*
|--------------------------------------------------------------------------
| Judul halaman.
|--------------------------------------------------------------------------
*/

$page_title = 'Profil - RAStory Bookstore';


/*
|--------------------------------------------------------------------------
| Ambil data User yang sedang login.
|--------------------------------------------------------------------------
*/

$currentUser = user();


/*
|--------------------------------------------------------------------------
| Ambil seluruh pesanan milik User.
|
| Pesanan dihubungkan dengan order_items dan books supaya
| riwayat pembelian dapat menampilkan nama buku yang dibeli.
|--------------------------------------------------------------------------
*/

$orderStmt = $pdo->prepare(
    '
    SELECT
        o.id AS order_id,
        o.order_number,
        o.total,
        o.payment_method,
        o.transfer_proof,
        o.status,
        o.address,
        o.created_at,
        oi.qty,
        oi.price AS item_price,
        b.title,
        b.image
    FROM orders o
    LEFT JOIN order_items oi
        ON oi.order_id = o.id
    LEFT JOIN books b
        ON b.id = oi.book_id
    WHERE o.user_id = ?
    ORDER BY
        o.created_at DESC,
        oi.id ASC
    '
);

$orderStmt->execute([
    $currentUser['id'],
]);

$purchaseRows = $orderStmt->fetchAll();


/*
|--------------------------------------------------------------------------
| Kelompokkan buku berdasarkan nomor pesanan.
|--------------------------------------------------------------------------
*/

$orders = [];

foreach ($purchaseRows as $row) {

    $orderId = (int) $row['order_id'];

    if (!isset($orders[$orderId])) {

        $orders[$orderId] = [
            'id' => $orderId,
            'order_number' => $row['order_number'],
            'total' => $row['total'],
            'payment_method' => $row['payment_method'],
            'transfer_proof' => $row['transfer_proof'],
            'status' => $row['status'],
            'address' => $row['address'],
            'created_at' => $row['created_at'],
            'items' => [],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Hanya masukkan item apabila pesanan memang memiliki buku.
    |--------------------------------------------------------------------------
    */

    if (!empty($row['title'])) {

        $orders[$orderId]['items'][] = [
            'title' => $row['title'],
            'image' => $row['image'],
            'qty' => (int) $row['qty'],
            'price' => $row['item_price'],
        ];
    }
}

require __DIR__ . '/includes/header.php';
?>


<main class="profile-page">

    <div class="profile-container">

        <!-- =====================================================
             PROFILE CARD
             ===================================================== -->

        <section class="profile-card">

            <div class="profile-avatar">
                <?= e(mb_strtoupper(mb_substr($currentUser['name'], 0, 1))) ?>
            </div>

            <div class="profile-data">

                <h1>
                    Profil Pengguna
                </h1>

                <div class="profile-row">
                    <strong>
                        Nama Pengguna
                    </strong>

                    <span>
                        <?= e($currentUser['name']) ?>
                    </span>
                </div>

                <div class="profile-row">
                    <strong>
                        Email
                    </strong>

                    <span>
                        <?= e($currentUser['email']) ?>
                    </span>
                </div>

            </div>

        </section>


        <!-- =====================================================
             PURCHASE HISTORY
             ===================================================== -->

        <section class="history-section">

            <div class="history-header">

                <div>
                    <h2>
                        Riwayat Pembelian Buku
                    </h2>

                    <p>
                        Berikut adalah daftar pesanan buku yang
                        pernah kamu lakukan.
                    </p>
                </div>

                <a
                    class="btn btn-outline"
                    href="index.php"
                >
                    Belanja Buku
                </a>

            </div>


            <?php if (!$orders): ?>

                <div class="empty-history">

                    <h3>
                        Belum ada riwayat pembelian.
                    </h3>

                    <p>
                        Yuk, pilih buku favoritmu dan lakukan
                        pembelian pertama.
                    </p>

                    <a
                        class="btn btn-primary"
                        href="index.php"
                    >
                        Lihat Buku
                    </a>

                </div>

            <?php else: ?>

                <div class="history-list">

                    <?php foreach ($orders as $order): ?>

                        <article class="history-card">

                            <div class="history-card-top">

                                <div>
                                    <span class="history-label">
                                        Nomor Pesanan
                                    </span>

                                    <strong>
                                        <?= e($order['order_number']) ?>
                                    </strong>
                                </div>

                                <span
                                    class="history-status history-status-<?= e(strtolower($order['status'])) ?>"
                                >
                                    <?= e($order['status']) ?>
                                </span>

                            </div>


                            <div class="history-meta">

                                <div>
                                    <span>
                                        Tanggal
                                    </span>

                                    <strong>
                                        <?= e($order['created_at']) ?>
                                    </strong>
                                </div>

                                <div>
                                    <span>
                                        Pembayaran
                                    </span>

                                    <strong>
                                        <?= $order['payment_method'] === 'BANK' ? 'Transfer Bank' : 'Bayar di Tempat (COD)' ?>
                                    </strong>
                                </div>

                                <div>
                                    <span>
                                        Total
                                    </span>

                                    <strong>
                                        <?= rupiah($order['total']) ?>
                                    </strong>
                                </div>

                            </div>


                            <div class="history-items">

                                <h3>
                                    Buku yang Dibeli
                                </h3>

                                <?php foreach ($order['items'] as $item): ?>

                                    <div class="history-item">

                                        <div class="history-book-image">

                                            <img
                                                src="assets/images/<?= e($item['image']) ?>"
                                                alt="<?= e($item['title']) ?>"
                                            >

                                        </div>

                                        <div class="history-book-info">

                                            <strong>
                                                <?= e($item['title']) ?>
                                            </strong>

                                            <span>
                                                <?= $item['qty'] ?> buku
                                            </span>

                                        </div>

                                        <div class="history-book-price">

                                            <?= rupiah(
                                                (float) $item['price']
                                                * $item['qty']
                                            ) ?>

                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                            <?php if ($order['payment_method'] === 'BANK' && !empty($order['transfer_proof'])): ?>
                                <div class="history-proof">
                                    <a
                                        class="history-proof-link"
                                        href="<?= e($order['transfer_proof']) ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        aria-label="Buka bukti transfer pesanan <?= e($order['order_number']) ?>"
                                    >
                                        <img
                                            src="<?= e($order['transfer_proof']) ?>"
                                            alt="Bukti transfer <?= e($order['order_number']) ?>"
                                            loading="lazy"
                                        >
                                    </a>
                                    <div class="history-proof-heading">
                                        <h3>Bukti Transfer</h3>
                                        <span>Foto bukti pembayaran untuk pesanan ini.</span>
                                        <span class="history-proof-hint">Klik foto untuk melihat ukuran penuh.</span>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>

    </div>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
