<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Halaman ini khusus akun pengguna biasa.
if (is_user()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare(
        'SELECT * FROM users WHERE email = ? AND role = \'user\' LIMIT 1'
    );
    $stmt->execute([$email]);
    $account = $stmt->fetch();

    if (!$account || !password_verify($password, $account['password'])) {
        $error = 'Email atau kata sandi pengguna salah.';
    } else {
        session_regenerate_id(true);

        // Sesi pengguna disimpan terpisah dari sesi admin.
        $_SESSION['user'] = [
            'id' => (int) $account['id'],
            'name' => $account['name'],
            'email' => $account['email'],
            'role' => 'user',
        ];

        redirect('index.php');
    }
}

$page_title = 'Masuk Pengguna - RAStory Bookstore';
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RAStory Bookstore - Masuk Pengguna</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-shell">
    <section class="auth-left">
        <h1>Masuk</h1>
        <div class="subtitle">Masukkan email dan kata sandi untuk masuk</div>

        <?php if ($error): ?>
            <div class="flash error auth-message"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($f = flash()): ?>
            <div class="flash <?= e($f['type']) ?> auth-message"><?= e($f['message']) ?></div>
        <?php endif; ?>

        <form class="auth-form" method="post" action="login.php">
            <div class="auth-field">
                <label>Email :</label>
                <div class="auth-input-wrap">
                    <input
                        name="email"
                        type="email"
                        value="<?= e($_POST['email'] ?? '') ?>"
                        placeholder="Masukkan email"
                        autocomplete="username"
                        required
                    >
                </div>
            </div>

            <div class="auth-field">
                <label>Kata Sandi :</label>
                <div class="auth-input-wrap">
                    <input
                        name="password"
                        type="password"
                        placeholder="Masukkan kata sandi"
                        autocomplete="current-password"
                        required
                    >
                </div>
            </div>

            <button class="auth-submit" type="submit">Masuk</button>
        </form>

        <div class="account-row">
            Belum punya akun? <a href="register.php">Buat Akun</a>
        </div>
    </section>

    <section class="auth-right">
        <img class="logo" src="assets/images/rastorebook.png" alt="RAStorebook">
        <h2>Selamat datang kembali!</h2>
        <img class="login-art" src="assets/images/login-books.png" alt="Ilustrasi buku">
        <div class="login-modes single">
            <div class="login-mode-current">Masuk Pengguna</div>
        </div>
    </section>
</div>

</body>
</html>
