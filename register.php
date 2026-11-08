<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (is_user()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (
        $name === ''
        || !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $error = 'Nama dan email harus diisi dengan benar.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $confirm) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO users(name, email, password, role)
                 VALUES(?, ?, ?, 'user')"
            );

            $stmt->execute([
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
            ]);

            flash(
                'success',
                'Akun berhasil dibuat. Silakan login.'
            );

            redirect('login.php');
        } catch (PDOException $e) {
            $error = $e->getCode() === '23000'
                ? 'Email sudah terdaftar.'
                : 'Registrasi gagal.';
        }
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <title>RAStory Bookstore - Daftar</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-shell">

    <section class="auth-left">
        <h1>Buat Akun</h1>

        <div class="subtitle">
            Buat akun RAStory Bookstore
        </div>

        <?php if ($error): ?>
            <div class="flash error auth-message">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form class="auth-form" method="post">
            <div class="auth-field">
                <label>Nama Lengkap :</label>
                <input
                    style="width: 100%; height: 62px; padding: 0 25px; border: 0; border-radius: 18px"
                    name="name"
                    required
                >
            </div>

            <div class="auth-field">
                <label>Email :</label>
                <input
                    style="width: 100%; height: 62px; padding: 0 25px; border: 0; border-radius: 18px"
                    type="email"
                    name="email"
                    required
                >
            </div>

            <div class="auth-field">
                <label>Kata Sandi :</label>
                <input
                    style="width: 100%; height: 62px; padding: 0 25px; border: 0; border-radius: 18px"
                    type="password"
                    name="password"
                    required
                >
            </div>

            <div class="auth-field">
                <label>Konfirmasi Kata Sandi :</label>
                <input
                    style="width: 100%; height: 62px; padding: 0 25px; border: 0; border-radius: 18px"
                    type="password"
                    name="confirm"
                    required
                >
            </div>

            <button
                class="auth-submit"
                type="submit"
            >
                Buat Akun
            </button>
        </form>

        <div class="account-row">
            Sudah punya akun?
            <a href="login.php">Masuk</a>
        </div>
    </section>

    <section class="auth-right">
        <img
            class="logo"
            src="assets/images/rastorebook.png"
            alt="RAStorebook"
        >

        <h2>Selamat Datang!</h2>

        <img
            class="login-art"
            src="assets/images/login-books.png"
            alt="Ilustrasi buku"
        >

        <div class="login-modes single">
            <div class="login-mode-current">Area Pengguna</div>
        </div>
    </section>

</div>

</body>
</html>
