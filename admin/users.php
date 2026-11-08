<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$page_title = 'Daftar Pengguna';

$rows = $pdo->query(
    "
    SELECT id, name, email, created_at
    FROM users
    WHERE role = 'user'
    ORDER BY created_at DESC
    "
)->fetchAll();

require __DIR__ . '/../includes/admin_header.php';
?>

<div class="admin-panel">
    <div class="toolbar">
        <h2>Pengguna Terdaftar</h2>

        <span class="badge">
            <?= count($rows) ?> Pengguna
        </span>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Terdaftar</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rows as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= e($row['name']) ?></td>
                        <td><?= e($row['email']) ?></td>
                        <td><?= e($row['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
