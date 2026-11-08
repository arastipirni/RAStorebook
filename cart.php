<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tambah buku dari link GET atau form POST.
if (
    (isset($_GET['add']) && $_GET['add'] !== '')
    || (
        $_SERVER['REQUEST_METHOD'] === 'POST'
        && ($_POST['action'] ?? '') === 'add'
    )
) {
    $id = (int) (
        $_GET['add']
        ?? $_POST['id']
        ?? 0
    );

    $qty = max(1, (int) ($_POST['qty'] ?? 1));

    $stmt = $pdo->prepare(
        'SELECT id, stock FROM books WHERE id = ?'
    );

    $stmt->execute([$id]);
    $book = $stmt->fetch();

    if (!$book) {
        flash('error', 'Buku tidak ditemukan.');
        redirect('index.php');
    }

    if ((int) $book['stock'] <= 0) {
        flash('error', 'Stok buku sedang habis.');
        redirect('index.php');
    }

    $oldQty = (int) ($_SESSION['cart'][$id] ?? 0);
    $_SESSION['cart'][$id] = min(
        (int) $book['stock'],
        $oldQty + $qty
    );

    // Kembali ke katalog tanpa menampilkan pesan agar user bisa
    // langsung melanjutkan memilih buku lain.
    redirect('index.php');
}

// Perbarui jumlah item di keranjang.
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'update'
) {
    $id = (int) ($_POST['id'] ?? 0);
    $qty = max(1, (int) ($_POST['qty'] ?? 1));

    $stmt = $pdo->prepare(
        'SELECT stock FROM books WHERE id = ?'
    );

    $stmt->execute([$id]);
    $stock = (int) $stmt->fetchColumn();

    if ($stock <= 0) {
        unset($_SESSION['cart'][$id]);
        flash('error', 'Buku sedang habis.');
    } else {
        $_SESSION['cart'][$id] = min($qty, $stock);
        flash('success', 'Keranjang diperbarui.');
    }

    redirect('cart.php');
}

// Hapus item dari keranjang.
if (isset($_GET['remove'])) {
    $id = (int) $_GET['remove'];

    unset($_SESSION['cart'][$id]);

    flash(
        'success',
        'Item dihapus dari keranjang.'
    );

    redirect('cart.php');
}

$items = cart_items($pdo);
$total = cart_total($pdo);
$page_title = 'Keranjang Belanja - RAStory Bookstore';

require __DIR__ . '/includes/header.php';
?>

<div class="page-wrap">
    <h1>Keranjang Belanja</h1>

    <?php if (!$items): ?>
        <div
            class="panel"
            style="text-align: center"
        >
            Keranjang masih kosong.
            <br><br>

            <a
                class="btn btn-primary"
                href="index.php"
            >
                Lihat Buku
            </a>
        </div>
    <?php else: ?>
        <div class="panel">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div
                                        style="display: flex; gap: 12px; align-items: center"
                                    >
                                        <img
                                            src="assets/images/<?= e($item['image']) ?>"
                                            alt="<?= e($item['title']) ?>"
                                            style="width: 45px; height: 60px; object-fit: contain"
                                        >

                                        <div>
                                            <strong>
                                                <?= e($item['title']) ?>
                                            </strong>
                                            <br>
                                            <small>
                                                <?= e($item['author']) ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <?= rupiah($item['price']) ?>
                                </td>

                                <td>
                                    <form
                                        method="post"
                                        style="display: flex; gap: 5px"
                                    >
                                        <input
                                            type="hidden"
                                            name="action"
                                            value="update"
                                        >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $item['id'] ?>"
                                        >

                                        <input
                                            type="number"
                                            name="qty"
                                            value="<?= $item['qty'] ?>"
                                            min="1"
                                            max="<?= $item['stock'] ?>"
                                            style="width: 65px; padding: 7px; border: 1px solid #cad7df; border-radius: 6px"
                                        >

                                        <button
                                            class="card-btn"
                                            type="submit"
                                        >
                                            Perbarui
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    <?= rupiah($item['subtotal']) ?>
                                </td>

                                <td>
                                    <a
                                        href="cart.php?remove=<?= $item['id'] ?>"
                                        data-confirm="Hapus buku ini?"
                                        style="color: #d22"
                                    >
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div
                style="display: flex; justify-content: space-between; align-items: center; gap: 15px; margin-top: 22px"
            >
                <strong
                    style="color: #1f5279; font-size: 18px"
                >
                    Total: <?= rupiah($total) ?>
                </strong>

                <a
                    class="btn btn-primary"
                    href="checkout.php"
                >
                    Lanjut Pembayaran
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
