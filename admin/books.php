<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$page_title = 'Kelola Buku';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $publishedYearRaw = trim($_POST['published_year'] ?? '');
    $publishedYear = $publishedYearRaw === '' ? null : (int) $publishedYearRaw;
    $category = (int) ($_POST['category_id'] ?? 0);
    $price = (float) ($_POST['price'] ?? 0);
    $stock = max(0, (int) ($_POST['stock'] ?? 0));
    $image = $editImage = '';
    $description = trim($_POST['description'] ?? '');

    $oldImage = '';
    if ($action === 'update' && $id) {
        $oldStmt = $pdo->prepare('SELECT image FROM books WHERE id = ?');
        $oldStmt->execute([$id]);
        $oldImage = (string)$oldStmt->fetchColumn();
    }

    if (!$title || !$author || $price < 0 || ($publishedYear !== null && ($publishedYear < 1000 || $publishedYear > 9999))) {
        flash('error', 'Lengkapi data buku.');

        redirect(
            'books.php' . ($id ? '?edit=' . $id : '')
        );
    }

    if (!empty($_FILES['cover']['name'])) {
        $file = $_FILES['cover'];
        if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 8 * 1024 * 1024) {
            flash('error', 'Cover harus berupa gambar maksimal 8 MB.');
            redirect('books.php' . ($id ? '?edit=' . $id : ''));
        }
        $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!isset($allowed[$mime])) {
            flash('error', 'Format cover harus JPG, PNG, atau WEBP.');
            redirect('books.php' . ($id ? '?edit=' . $id : ''));
        }
        $image = 'cover_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
        if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/../assets/images/' . $image)) {
            flash('error', 'Cover gagal diunggah.');
            redirect('books.php' . ($id ? '?edit=' . $id : ''));
        }
        if ($oldImage) @unlink(__DIR__ . '/../assets/images/' . basename($oldImage));
    } else {
        $image = $oldImage;
        if ($action === 'add' && !$image) {
            flash('error', 'Cover buku wajib diunggah.');
            redirect('books.php');
        }
    }

    if ($action === 'add') {
        $stmt = $pdo->prepare(
            'INSERT INTO books(
                category_id,
                title,
                author,
                published_year,
                price,
                image,
                description,
                stock
            ) VALUES(?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $category ?: null,
            $title,
            $author,
            $publishedYear,
            $price,
            $image,
            $description,
            $stock,
        ]);

        flash(
            'success',
            'Buku berhasil ditambahkan.'
        );
    } elseif ($action === 'update' && $id) {
        $stmt = $pdo->prepare(
            'UPDATE books
             SET
                category_id = ?,
                title = ?,
                author = ?,
                published_year = ?,
                price = ?,
                image = ?,
                description = ?,
                stock = ?
             WHERE id = ?'
        );

        $stmt->execute([
            $category ?: null,
            $title,
            $author,
            $publishedYear,
            $price,
            $image,
            $description,
            $stock,
            $id,
        ]);

        flash(
            'success',
            'Buku berhasil diperbarui.'
        );
    }

    redirect('books.php');
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare(
        'DELETE FROM books WHERE id = ?'
    );

    $stmt->execute([
        (int) $_GET['delete'],
    ]);

    flash(
        'success',
        'Buku berhasil dihapus.'
    );

    redirect('books.php');
}

$edit = null;

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare(
        'SELECT * FROM books WHERE id = ?'
    );

    $stmt->execute([
        (int) $_GET['edit'],
    ]);

    $edit = $stmt->fetch();
}

$cats = $pdo->query(
    'SELECT * FROM categories ORDER BY sort_order, id'
)->fetchAll();

$rows = $pdo->query(
    "
    SELECT
        b.*,
        c.name AS category_name
    FROM books b
    LEFT JOIN categories c
        ON c.id = b.category_id
    ORDER BY b.id
    "
)->fetchAll();

require __DIR__ . '/../includes/admin_header.php';
?>

<div class="admin-panel">
    <div class="toolbar">
        <h2>
            <?= $edit ? 'Edit Buku' : 'Tambah Buku' ?>
        </h2>

        <a
            class="btn btn-outline admin-actions"
            href="books.php"
        >
            Atur Ulang
        </a>
    </div>

    <form class="form-grid" method="post" enctype="multipart/form-data">
        <input
            type="hidden"
            name="action"
            value="<?= $edit ? 'update' : 'add' ?>"
        >

        <input
            type="hidden"
            name="id"
            value="<?= $edit['id'] ?? 0 ?>"
        >

        <div>
            <label>Judul Buku</label>
            <input
                name="title"
                value="<?= e($edit['title'] ?? '') ?>"
                required
            >
        </div>

        <div>
            <label>Penulis</label>
            <input
                name="author"
                value="<?= e($edit['author'] ?? '') ?>"
                required
            >
        </div>

        <div>
            <label>Tahun Terbit</label>
            <input
                type="number"
                name="published_year"
                min="1000"
                max="9999"
                placeholder="Contoh: 2002"
                value="<?= e($edit['published_year'] ?? '') ?>"
            >
        </div>

        <div>
            <label>Kategori</label>

            <select name="category_id">
                <option value="0">
                    - pilih kategori -
                </option>

                <?php foreach ($cats as $category): ?>
                    <option
                        value="<?= $category['id'] ?>"
                        <?= $edit && $edit['category_id'] == $category['id'] ? 'selected' : '' ?>
                    >
                        <?= e($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>Harga</label>
            <input
                type="number"
                name="price"
                min="0"
                step="500"
                value="<?= e($edit['price'] ?? 0) ?>"
            >
        </div>

        <div>
            <label>Upload Cover Buku</label>

            <div class="cover-upload-box">
                <input
                    type="file"
                    name="cover"
                    id="cover"
                    class="cover-file-input"
                    accept="image/jpeg,image/png,image/webp"
                    <?= $edit ? '' : 'required' ?>
                >

                <label for="cover" class="cover-choose-btn">
                    Pilih Foto Cover
                </label>

                <span id="cover-file-name" class="cover-file-name">
                    <?= ($edit && !empty($edit['image'])) ? 'Pilih foto baru jika ingin mengganti cover.' : 'Belum ada foto yang dipilih.' ?>
                </span>
            </div>

            <div class="admin-cover-live-preview" id="cover-live-preview" <?= ($edit && !empty($edit['image'])) ? '' : 'hidden' ?>>
                <img
                    id="cover-live-image"
                    src="<?= ($edit && !empty($edit['image'])) ? '../assets/images/' . e($edit['image']) : '' ?>"
                    alt="Pratinjau cover buku"
                >
                <div>
                    <strong>Pratinjau Cover</strong>
                    <small>Foto cover akan terlihat di sini sebelum disimpan.</small>
                </div>
            </div>
        </div>

        <div>
            <label>Stok</label>
            <input
                type="number"
                name="stock"
                min="0"
                value="<?= e($edit['stock'] ?? 10) ?>"
            >
        </div>

        <div class="full">
            <label>Deskripsi</label>
            <textarea
                name="description"
                rows="6"
            ><?= e($edit['description'] ?? '') ?></textarea>
        </div>

        <div class="full">
            <button
                class="btn btn-primary"
                type="submit"
            >
                Simpan Buku
            </button>
        </div>
    </form>
</div>

<div class="admin-panel">
    <h2>Semua Buku</h2>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Cover</th>
                    <th>Buku</th>
                    <th>Tahun Terbit</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rows as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>

                        <td>
                            <img
                                src="../assets/images/<?= e($row['image']) ?>"
                                alt="<?= e($row['title']) ?>"
                                style="width: 45px; height: 58px; object-fit: contain"
                            >
                        </td>

                        <td>
                            <strong>
                                <?= e($row['title']) ?>
                            </strong>
                            <br>
                            <span style="color: #777">
                                <?= e($row['author']) ?>
                            </span>
                        </td>

                        <td>
                            <?= !empty($row['published_year']) ? e($row['published_year']) : '-' ?>
                        </td>

                        <td>
                            <?= e($row['category_name'] ?? '-') ?>
                        </td>

                        <td>
                            <?= rupiah($row['price']) ?>
                        </td>

                        <td>
                            <?= $row['stock'] ?>
                        </td>

                        <td class="actions">
                            <a
                                class="btn btn-outline admin-actions"
                                href="?edit=<?= $row['id'] ?>"
                            >
                                Edit
                            </a>

                            <a
                                class="btn admin-actions"
                                style="color: #d11; border-color: #d11"
                                data-confirm="Hapus buku ini?"
                                href="?delete=<?= $row['id'] ?>"
                            >
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const coverInput = document.getElementById('cover');
const coverFileName = document.getElementById('cover-file-name');
const coverPreview = document.getElementById('cover-live-preview');
const coverPreviewImage = document.getElementById('cover-live-image');

if (coverInput) {
    coverInput.addEventListener('change', function () {
        const file = this.files && this.files[0];

        if (!file) {
            return;
        }

        coverFileName.textContent = file.name;

        const reader = new FileReader();
        reader.onload = function (event) {
            coverPreviewImage.src = event.target.result;
            coverPreview.hidden = false;
        };
        reader.readAsDataURL(file);
    });
}
</script>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>

