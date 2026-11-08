<?php
/**
 * File: admin/order_notifications.php
 * Deskripsi: Menyediakan jumlah pesanan baru untuk pemberitahuan dashboard admin.
 * Initial State: Administrator sudah login.
 * Final State: Browser menerima jumlah pesanan berstatus Menunggu dan pesanan
 *              terbaru secara berkala tanpa memuat ulang seluruh halaman.
 * Author: [Isi nama peserta]
 * Version: 1.0 | Tanggal revisi: 05 November 2026
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('X-Content-Type-Options: nosniff');

if (!is_admin()) {
    http_response_code(401);
    echo json_encode(['error' => 'Akses admin diperlukan.']);
    exit;
}

// Endpoint hanya membaca data; lepaskan lock sesi agar tab admin lain tetap responsif.
session_write_close();

try {
    $pendingCount = (int) $pdo->query(
        "SELECT COUNT(*) FROM orders WHERE status = 'Menunggu'"
    )->fetchColumn();

    $latestOrder = $pdo->query(
        "SELECT id, order_number, customer_name, created_at
         FROM orders
         WHERE status = 'Menunggu'
         ORDER BY id DESC
         LIMIT 1"
    )->fetch() ?: null;

    echo json_encode([
        'pending_count' => $pendingCount,
        'latest_order' => $latestOrder ? [
            'id' => (int) $latestOrder['id'],
            'order_number' => (string) $latestOrder['order_number'],
            'customer_name' => (string) $latestOrder['customer_name'],
            'created_at' => (string) $latestOrder['created_at'],
        ] : null,
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Throwable $error) {
    error_log('[RAStory Bookstore] Gagal mengambil notifikasi pesanan: ' . $error->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Notifikasi pesanan belum dapat dimuat.']);
}
