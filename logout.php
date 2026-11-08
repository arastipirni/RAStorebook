<?php
require_once __DIR__ . '/includes/functions.php';

// Hanya keluarkan akun pengguna; sesi admin tetap aktif jika ada.
unset($_SESSION['user']);

redirect('login.php');
