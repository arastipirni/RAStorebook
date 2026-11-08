<?php
/**
 * Koneksi database MySQL untuk project BookStore.
 *
 * Panduan singkat:
 * 1. Nyalakan Apache dan MySQL di XAMPP.
 * 2. Import file database.sql melalui phpMyAdmin.
 * 3. Pastikan nama database adalah "bookstore".
 */

$host = '127.0.0.1';
$db = 'bookstore';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Menambahkan kolom tahun terbit ke database lama tanpa membuat database baru.
    // Ini hanya dijalankan bila kolom tersebut belum ada.
    $columnCheck = $pdo->query("SHOW COLUMNS FROM books LIKE 'published_year'");
    if (!$columnCheck->fetch()) {
        $pdo->exec("ALTER TABLE books ADD COLUMN published_year SMALLINT UNSIGNED NULL AFTER author");
    }

    // Buku pada contoh yang sedang digunakan user: The Shark Caller, tahun 2002.
    $pdo->exec("UPDATE books SET published_year = 2002 WHERE title = 'The Shark Caller' AND (published_year IS NULL OR published_year = 0)");
} catch (PDOException $e) {
    exit(
        'Koneksi database gagal. '
        . 'Pastikan MySQL XAMPP aktif dan database sudah di-import.'
    );
}
?>
