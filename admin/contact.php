<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$page_title = 'Pesan Pengguna - Admin';

try {
    $stmt = $pdo->query(
        'SELECT id, name, email, subject, message, created_at FROM messages ORDER BY created_at DESC, id DESC'
    );
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $errorMessage = '';
} catch (PDOException $e) {
    $contacts = [];
    $errorMessage = 'Pesan tidak dapat dibaca dari database.';
}

require __DIR__ . '/../includes/admin_header.php';
?>

<main class="admin-page">
    <div class="admin-page-heading">
        <div>
            <h1>Pesan Pengguna</h1>
            <p>Semua pesan yang dikirim melalui halaman Kontak.</p>
        </div>
        <div class="message-total">
            <?= count($contacts) ?> Pesan
        </div>
    </div>

    <?php if ($errorMessage !== ''): ?>
        <div class="message-error-box">
            <?= e($errorMessage) ?>
        </div>
    <?php endif; ?>

    <?php if ($errorMessage === '' && count($contacts) === 0): ?>
        <div class="admin-message-empty">
            <strong>Belum ada pesan.</strong>
            <p>Pesan dari pengguna akan muncul di sini.</p>
        </div>
    <?php endif; ?>

    <div class="admin-message-list">
        <?php foreach ($contacts as $contact): ?>
            <article class="admin-message-card">
                <div class="admin-message-head">
                    <div>
                        <h2><?= e($contact['subject'] ?: 'Kontak') ?></h2>
                        <div class="admin-message-sender">
                            <strong><?= e($contact['name']) ?></strong>
                            <a href="mailto:<?= e($contact['email']) ?>"><?= e($contact['email']) ?></a>
                        </div>
                    </div>
                    <time><?= e(date('d M Y, H:i', strtotime($contact['created_at']))) ?></time>
                </div>

                <div class="admin-message-body">
                    <?= nl2br(e($contact['message'])) ?>
                </div>

                <div class="admin-message-actions">
                    <a
                        href="mailto:<?= e($contact['email']) ?>?subject=Re:%20<?= urlencode($contact['subject'] ?: 'Kontak') ?>"
                        class="btn btn-primary"
                    >
                        Balas Email
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</main>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>

<style>
.admin-page {
    padding: 30px 37px 50px;
    min-height: calc(100vh - 108px);
}

.admin-page-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
}

.admin-page-heading h1 {
    margin: 0;
    color: #315975;
    font-size: 28px;
}

.admin-page-heading p {
    margin: 7px 0 0;
    color: #5f7787;
    font-size: 13px;
}

.message-total {
    padding: 10px 16px;
    border-radius: 999px;
    background: #fff;
    border: 1px solid #c5d3dd;
    color: #315975;
    font-size: 13px;
    font-weight: 600;
}

.message-error-box {
    margin-bottom: 20px;
    padding: 14px 16px;
    border-radius: 8px;
    background: #fff1f1;
    color: #a33a3a;
    border: 1px solid #efc0c0;
    font-size: 13px;
}

.admin-message-list {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.admin-message-card {
    background: #fff;
    border: 1px solid #d5e0e7;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 14px rgba(31,82,121,.07);
}

.admin-message-head {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 14px;
}

.admin-message-head h2 {
    margin: 0 0 8px;
    color: #315975;
    font-size: 17px;
}

.admin-message-sender {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    color: #607582;
    font-size: 12px;
}

.admin-message-sender a {
    color: #1f6a9e;
}

.admin-message-head time {
    color: #7a8b95;
    font-size: 11px;
    white-space: nowrap;
}

.admin-message-body {
    color: #2b3e49;
    font-size: 13px;
    line-height: 1.7;
    padding-top: 14px;
    border-top: 1px solid #e8eef2;
}

.admin-message-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 16px;
}

.admin-message-empty {
    background: #fff;
    border: 1px solid #d5e0e7;
    border-radius: 12px;
    padding: 25px;
    color: #5f7787;
}

@media (max-width: 700px) {
    .admin-page {
        padding: 22px 16px 40px;
    }

    .admin-page-heading,
    .admin-message-head {
        align-items: flex-start;
        flex-direction: column;
    }

    .admin-message-actions {
        justify-content: stretch;
    }

    .admin-message-actions .btn {
        width: 100%;
    }
}
</style>
