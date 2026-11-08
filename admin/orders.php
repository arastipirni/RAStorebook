<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$page_title = 'Pesanan';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? 'Menunggu';

    $allowedStatuses = [
        'Menunggu',
        'Diproses',
        'Dikirim',
        'Selesai',
        'Dibatalkan',
    ];

    if (in_array($status, $allowedStatuses, true)) {
        $stmt = $pdo->prepare(
            'UPDATE orders SET status = ? WHERE id = ?'
        );

        $stmt->execute([
            $status,
            (int) $_POST['id'],
        ]);

        flash(
            'success',
            'Status pesanan diperbarui.'
        );
    }

    redirect('orders.php');
}

$rows = $pdo->query(
    "
    SELECT
        o.*,
        u.name AS user_name,
        u.email
    FROM orders o
    JOIN users u
        ON u.id = o.user_id
    ORDER BY o.created_at DESC
    "
)->fetchAll();

require __DIR__ . '/../includes/admin_header.php';
?>

<div class="admin-panel">
    <div class="toolbar">
        <h2>Daftar Pesanan</h2>

        <span class="badge">
            <?= count($rows) ?> Pesanan
        </span>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nomor Pesanan</th>
                    <th>Pengguna</th>
                    <th>Telepon</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Bukti Transfer</th>
                    <th>Status</th>
                    <th>Alamat</th>
                    <th>Tanggal</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><strong><?= e($row['order_number']) ?></strong></td>

                        <td>
                            <?= e($row['user_name']) ?>
                            <br>
                            <small>
                                <?= e($row['email']) ?>
                            </small>
                        </td>

                        <td>
                            <?= e($row['phone'] ?: '-') ?>
                        </td>

                        <td>
                            <?= rupiah($row['total']) ?>
                        </td>

                        <td>
                            <?= $row['payment_method'] === 'BANK' ? 'Transfer Bank' : 'Bayar di Tempat (COD)' ?>
                            <?php if ($row['payment_method'] === 'BANK'): ?>
                                <br><small><?= e($row['bank_name']) ?> — <?= e($row['bank_account']) ?></small>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (!empty($row['transfer_proof'])): ?>
                                <a class="proof-link" href="../<?= e($row['transfer_proof']) ?>" target="_blank" rel="noopener">
                                    <img src="../<?= e($row['transfer_proof']) ?>" alt="Bukti transfer <?= e($row['order_number']) ?>" class="proof-thumb">
                                    <small>Lihat bukti</small>
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>

                        <td>
                            <form method="post">
                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $row['id'] ?>"
                                >

                                <select
                                    name="status"
                                    onchange="this.form.submit()"
                                    style="padding: 6px; border: 1px solid #ccd8e0; border-radius: 6px"
                                >
                                    <?php foreach (
                                        [
                                            'Menunggu',
                                            'Diproses',
                                            'Dikirim',
                                            'Selesai',
                                            'Dibatalkan',
                                        ] as $status
                                    ): ?>
                                        <option
                                            value="<?= $status ?>"
                                            <?= $row['status'] === $status ? 'selected' : '' ?>
                                        >
                                            <?= $status ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>

                        <td>
                            <?= e($row['address']) ?>
                        </td>

                        <td>
                            <?= e(tanggal_indonesia($row['created_at'], true)) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
