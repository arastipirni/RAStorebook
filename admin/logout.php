<?php
require_once __DIR__ . '/../includes/functions.php';

// Hanya keluarkan akun admin; sesi pengguna tetap aktif jika ada.
unset($_SESSION['admin'], $_SESSION['admin_preview']); // Sesi user tidak ikut dihapus.

redirect('login.php');
