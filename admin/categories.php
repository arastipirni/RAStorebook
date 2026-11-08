<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$page_title = 'Kategori';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $sortOrder = (int) ($_POST['sort_order'] ?? 0);

    if ($name === '') {
        flash('error', 'Nama kategori wajib diisi.');
    } else {
        try {
            if ($action === 'add') {
                if ($sortOrder <= 0) {
                    $sortOrder = ((int)$pdo->query('SELECT COALESCE(MAX(sort_order),0) FROM categories')->fetchColumn()) + 1;
                }
                $stmt = $pdo->prepare(
                    'INSERT INTO categories(name, sort_order)
                     VALUES(?, ?)'
                );

                $stmt->execute([
                    $name,
                    $sortOrder,
                ]);

                flash(
                    'success',
                    'Kategori ditambahkan.'
                );
            } elseif ($action === 'update' && $id) {
                $stmt = $pdo->prepare(
                    'UPDATE categories
                     SET name = ?, sort_order = ?
                     WHERE id = ?'
                );

                $stmt->execute([
                    $name,
                    $sortOrder,
                    $id,
                ]);

                flash(
                    'success',
                    'Kategori diperbarui.'
                );
            }
        } catch (PDOException $e) {
            flash(
                'error',
                'Kategori tidak bisa disimpan. '
                . 'Pastikan namanya belum digunakan.'
            );
        }
    }

    redirect('categories.php');
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    try {
        $stmt = $pdo->prepare(
            'DELETE FROM categories WHERE id = ?'
        );

        $stmt->execute([$id]);

        flash(
            'success',
            'Kategori dihapus.'
        );
    } catch (PDOException $e) {
        flash(
            'error',
            'Kategori masih digunakan oleh buku. '
            . 'Hapus atau pindahkan bukunya dulu.'
        );
    }

    redirect('categories.php');
}

$edit = null;

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare(
        'SELECT * FROM categories WHERE id = ?'
    );

    $stmt->execute([
        (int) $_GET['edit'],
    ]);

    $edit = $stmt->fetch();
}

$rows = $pdo->query(
    'SELECT * FROM categories ORDER BY sort_order, id'
)->fetchAll();

require __DIR__ . '/../includes/admin_header.php';
?>

<div class="admin-panel">
    <div class="toolbar">
        <h2>
            <?= $edit ? 'Edit Kategori' : 'Tambah Kategori' ?>
        </h2>

        <a
            class="btn btn-outline admin-actions"
            href="categories.php"
        >
            Atur Ulang
        </a>
    </div>

    <form class="form-grid" method="post">
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
            <label>Nama Kategori</label>
            <input
                name="name"
                value="<?= e($edit['name'] ?? '') ?>"
                required
            >
        </div>

        <div>
            <label>Urutan</label>
            <input
                type="number"
                name="sort_order"
                value="<?= e($edit['sort_order'] ?? (((int)$pdo->query('SELECT COALESCE(MAX(sort_order),0) FROM categories')->fetchColumn()) + 1)) ?>"
            >
        </div>

        <div class="full">
            <button
                class="btn btn-primary"
                type="submit"
            >
                Simpan Kategori
            </button>
        </div>
    </form>
</div>

<div class="admin-panel">
    <h2>Daftar Kategori</h2>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Urutan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rows as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= e($row['name']) ?></td>
                        <td><?= e($row['sort_order']) ?></td>

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
                                data-confirm="Hapus kategori ini?"
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

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
