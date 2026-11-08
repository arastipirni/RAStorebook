<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

require_user();

$items = cart_items($pdo);

if (!$items) {
    redirect('cart.php');
}

$total = cart_total($pdo);
$error = '';

// Rekening RAStory (contoh/placeholder)
const RASTORY_BCA_ACCOUNT = '0922131430';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $method = strtolower(trim($_POST['payment_method'] ?? ''));
    $bankName = 'Bank BCA';
    $bankAccount = RASTORY_BCA_ACCOUNT;
    $proofPath = null;

    // Simpan kembali isian form supaya tidak hilang ketika validasi gagal.
    $oldAddress = $address;
    $oldPhone = $phone;
    $oldMethod = $method;
    $oldBankName = $bankName;
    $oldBankAccount = $bankAccount;

    if ($address === '' || $phone === '' || !in_array($method, ['cod', 'bank'], true)) {
        $error = 'Lengkapi alamat pengiriman, nomor telepon, dan metode pembayaran.';
    } elseif ($method === 'bank' && empty($_FILES['transfer_proof']['name'])) {
        $error = 'Untuk transfer bank, bukti transfer wajib diunggah.';
    } else {
        try {
            if ($method === 'bank') {
                $file = $_FILES['transfer_proof'];
                $extension = uploaded_image_extension($file, 5 * 1024 * 1024);
                $uploadDir = __DIR__ . '/assets/uploads/transfers';
                ensure_upload_directory($uploadDir);

                $filename = 'transfer_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
                $target = $uploadDir . DIRECTORY_SEPARATOR . $filename;

                if (!move_uploaded_file($file['tmp_name'], $target)) {
                    throw new RuntimeException('Bukti transfer gagal disimpan. Coba lagi.');
                }

                $proofPath = 'assets/uploads/transfers/' . $filename;
            }

            $pdo->beginTransaction();
            $orderTotal = 0.0;

            // Kunci stok saat checkout agar jumlah stok tidak bentrok dengan pesanan lain.
            $freshItems = [];
            foreach ($items as $item) {
                $stmt = $pdo->prepare('SELECT id, title, price, stock FROM books WHERE id = ? FOR UPDATE');
                $stmt->execute([(int)$item['id']]);
                $fresh = $stmt->fetch();
                if (!$fresh || (int)$fresh['stock'] < (int)$item['qty']) {
                    throw new RuntimeException('Stok buku tidak cukup: ' . ($fresh['title'] ?? $item['title']));
                }
                $freshItems[] = $fresh + ['qty' => (int)$item['qty']];
                $orderTotal += (float)$fresh['price'] * (int)$item['qty'];
            }

            $orderNumber = generate_order_number($pdo);
            $stmt = $pdo->prepare(
                'INSERT INTO orders(order_number,user_id,customer_name,phone,address,payment_method,bank_name,bank_account,transfer_proof,total) VALUES(?,?,?,?,?,?,?,?,?,?)'
            );
            $stmt->execute([
                $orderNumber,
                user()['id'],
                user()['name'],
                $phone,
                $address,
                strtoupper($method === 'cod' ? 'COD' : 'BANK'),
                $method === 'bank' ? $bankName : null,
                $method === 'bank' ? $bankAccount : null,
                $proofPath,
                $orderTotal,
            ]);
            $orderId = $pdo->lastInsertId();

            $itemStmt = $pdo->prepare('INSERT INTO order_items(order_id,book_id,qty,price,subtotal) VALUES(?,?,?,?,?)');
            $stockStmt = $pdo->prepare('UPDATE books SET stock = stock - ? WHERE id = ? AND stock >= ?');

            foreach ($freshItems as $item) {
                $qty = (int)$item['qty'];
                $price = (float)$item['price'];
                $stockStmt->execute([$qty, (int)$item['id'], $qty]);
                if ($stockStmt->rowCount() !== 1) {
                    throw new RuntimeException('Stok buku tidak cukup: ' . $item['title']);
                }
                $itemStmt->execute([
                    $orderId,
                    (int)$item['id'],
                    $qty,
                    $price,
                    $price * $qty,
                ]);
            }

            $pdo->commit();
            $_SESSION['cart'] = [];
            redirect('order_success.php?id=' . $orderId);
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if ($proofPath) {
                @unlink(__DIR__ . '/' . $proofPath);
            }
            $error = $e->getMessage();
        }
    }
}

$page_title = 'Pembayaran - RAStory Bookstore';

require __DIR__ . '/includes/header.php';
?>

<div class="page-wrap">
    <h1>Pembayaran Pesanan</h1>

    <div class="panel">
        <p>
            Total pesanan:
            <strong><?= rupiah($total) ?></strong>
        </p>

        <?php if ($error): ?>
            <div class="flash error">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form class="form-grid" method="post" enctype="multipart/form-data" id="checkout-form">
            <div class="full">
                <label>Alamat Pengiriman</label>
                <textarea
                    name="address"
                    rows="4"
                    required
                ><?= e($oldAddress ?? '') ?></textarea>
            </div>

            <div class="full">
                <label for="checkout-phone">Nomor Telepon</label>
                <input
                    id="checkout-phone"
                    type="tel"
                    name="phone"
                    inputmode="tel"
                    autocomplete="tel"
                    placeholder="Contoh: 081234567890"
                    value="<?= e($oldPhone ?? '') ?>"
                    required
                >
            </div>

            <div>
                <label>Metode Pembayaran</label>
                <select name="payment_method" id="payment_method">
                    <option value="cod" <?= (($oldMethod ?? '') === 'cod' || ($oldMethod ?? '') === '') ? 'selected' : '' ?>>Bayar di Tempat (COD)</option>
                    <option value="bank" <?= (($oldMethod ?? '') === 'bank') ? 'selected' : '' ?>>Transfer Bank</option>
                </select>
            </div>

            <div class="bank-fields full" id="bank-fields">
                <div class="payment-account-card full">
                    <div class="payment-account-head">
                        <strong>Rekening Pembayaran RAStorebook</strong>
                        <span>Transfer ke rekening berikut</span>
                    </div>

                    <div class="payment-account-info">
                        <div>
                            <small>Bank</small>
                            <strong>BCA</strong>
                        </div>
                        <div>
                            <small>Atas Nama</small>
                            <strong>RAStorebook</strong>
                        </div>
                        <div class="payment-account-number">
                            <small>Nomor Rekening</small>
                            <strong><?= e(RASTORY_BCA_ACCOUNT) ?></strong>
                        </div>
                    </div>
                </div>

                <div class="full">
                    <label>Bukti Transfer</label>
                    <input type="file" name="transfer_proof" id="transfer_proof" accept="image/jpeg,image/png,image/webp">
                    <small>Format JPG, PNG, atau WEBP, maksimal 5 MB.</small>
                    <div id="proof-preview" class="proof-preview" hidden>
                        <img id="proof-preview-image" src="" alt="Pratinjau bukti transfer">
                        <span>Pratinjau bukti transfer</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; align-items: end">
                <button
                    class="btn btn-primary"
                    type="submit"
                >
                    Buat Pesanan
                </button>
            </div>
        </form>
    </div>

<script>
const paymentMethod = document.getElementById('payment_method');
const bankFields = document.getElementById('bank-fields');
const proofInput = document.getElementById('transfer_proof');
const proofPreview = document.getElementById('proof-preview');
const proofPreviewImage = document.getElementById('proof-preview-image');

function toggleBankFields() {
    const isBank = paymentMethod.value === 'bank';
    bankFields.hidden = !isBank;
    bankFields.style.display = isBank ? 'grid' : 'none';
    proofInput.required = isBank;
}

if (proofInput) {
    proofInput.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (!file) {
            proofPreview.hidden = true;
            proofPreviewImage.removeAttribute('src');
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran foto maksimal 5 MB.');
            this.value = '';
            proofPreview.hidden = true;
            return;
        }
        const reader = new FileReader();
        reader.onload = function (event) {
            proofPreviewImage.src = event.target.result;
            proofPreview.hidden = false;
        };
        reader.readAsDataURL(file);
    });
}

paymentMethod.addEventListener('change', toggleBankFields);
toggleBankFields();
</script>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
