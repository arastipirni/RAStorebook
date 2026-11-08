<?php
/**
 * File helper utama.
 * Semua fungsi kecil dikumpulkan di sini supaya file halaman lebih mudah dibaca.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pindahkan sesi admin lama ke slot admin agar sesi pengguna dan admin
// dapat berjalan berdampingan tanpa saling menimpa.
if (
    isset($_SESSION['user']['role'])
    && $_SESSION['user']['role'] === 'admin'
    && !isset($_SESSION['admin'])
) {
    $_SESSION['admin'] = $_SESSION['user'];
    unset($_SESSION['user']);
}

/** Escape teks agar aman ditampilkan di HTML. */
function e($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

/** Ubah angka menjadi format Rupiah. */
function rupiah($number)
{
    return 'Rp ' . number_format(
        (float) $number,
        0,
        ',',
        '.'
    );
}

/** Format tanggal dengan nama hari dan bulan Bahasa Indonesia. */
function tanggal_indonesia($value, bool $withTime = false): string
{
    if (empty($value)) {
        return '-';
    }

    $timestamp = is_numeric($value) ? (int) $value : strtotime((string) $value);
    if ($timestamp === false || $timestamp <= 0) {
        return '-';
    }

    $hari = [
        1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis',
        5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu',
    ];
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    $result = $hari[(int) date('N', $timestamp)] . ', '
        . date('j', $timestamp) . ' '
        . $bulan[(int) date('n', $timestamp)] . ' '
        . date('Y', $timestamp);

    return $withTime ? $result . ' • ' . date('H:i', $timestamp) : $result;
}

/**
 * Cek apakah halaman publik sedang dibuka melalui pratinjau admin.
 * Penanda ini memakai URL, bukan session, supaya tab user tidak ikut berubah.
 */
function is_admin_preview(): bool
{
    $preview = $_GET['admin_preview'] ?? $_POST['admin_preview'] ?? '';

    return is_admin() && (string) $preview === '1';
}

/** Redirect ke halaman lain lalu hentikan script. */
function redirect($url)
{
    // Pertahankan mode pratinjau hanya pada halaman publik di tab ini.
    if (is_admin_preview()) {
        $parts = parse_url($url);
        $path = $parts['path'] ?? '';
        $isAdminPath = preg_match('~(^|/)admin(/|$)~i', $path) === 1;
        $isExternalUrl = isset($parts['scheme']) || isset($parts['host']);

        if (!$isAdminPath && !$isExternalUrl && strpos($url, 'admin_preview=') === false) {
            $url .= (strpos($url, '?') === false ? '?' : '&') . 'admin_preview=1';
        }
    }

    header('Location: ' . $url);
    exit;
}

/** Ambil data user yang sedang login. */
function user()
{
    return $_SESSION['user'] ?? null;
}

/** Cek apakah ada sesi pengguna atau admin yang sedang aktif. */
function is_logged_in()
{
    return is_user() || is_admin();
}

/** Ambil data admin yang sedang login. */
function admin_user()
{
    return $_SESSION['admin'] ?? null;
}

/** Cek sesi admin. Sesi ini terpisah dari sesi pengguna biasa. */
function is_admin()
{
    return isset($_SESSION['admin'])
        && ($_SESSION['admin']['role'] ?? '') === 'admin';
}

/** Cek sesi pengguna biasa. */
function is_user()
{
    return isset($_SESSION['user'])
        && ($_SESSION['user']['role'] ?? '') === 'user';
}

/** Membuat nomor pesanan unik agar setiap transaksi mudah dikenali. */
function generate_order_number(PDO $pdo)
{
    do {
        $number = 'ORD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM orders WHERE order_number = ?');
        $stmt->execute([$number]);
    } while ((int)$stmt->fetchColumn() > 0);

    return $number;
}

/** Memeriksa apakah stok buku masih tersedia. */
function book_available(array $book): bool
{
    return (int)($book['stock'] ?? 0) > 0;
}

/** Validasi upload gambar dan kembalikan ekstensi aman. */
function uploaded_image_extension(array $file, int $maxBytes = 5242880): string
{
    if (!isset($file['error'], $file['tmp_name'], $file['size']) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Foto gagal diunggah. Silakan pilih foto lagi.');
    }

    if ((int)$file['size'] <= 0 || (int)$file['size'] > $maxBytes) {
        throw new RuntimeException('Ukuran foto terlalu besar. Maksimal 5 MB.');
    }

    $mime = '';
    if (class_exists('finfo')) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = (string)$finfo->file($file['tmp_name']);
    }
    if ($mime === '' || $mime === 'application/octet-stream') {
        $imageInfo = @getimagesize($file['tmp_name']);
        $mime = is_array($imageInfo) && isset($imageInfo['mime']) ? (string)$imageInfo['mime'] : '';
    }
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Format foto harus JPG, PNG, atau WEBP.');
    }

    return $allowed[$mime];
}

/** Pastikan folder upload tersedia dan dapat ditulis. */
function ensure_upload_directory(string $directory): void
{
    if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
        throw new RuntimeException('Folder upload tidak dapat dibuat.');
    }

    if (!is_writable($directory)) {
        throw new RuntimeException('Folder upload tidak dapat ditulis oleh PHP.');
    }
}

/** Melindungi halaman yang dapat dibuka oleh user atau admin yang sudah login. */
function require_login()
{
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

/** Halaman yang hanya boleh dibuka admin. */
function require_admin()
{
    if (!is_admin()) {
        // Fungsi ini dipanggil dari dalam folder admin/.
        redirect('login.php');
    }
}

/** Halaman yang hanya boleh dibuka user. */
function require_user()
{
    if (!is_user()) {
        redirect('login.php');
    }
}

/** Jumlah seluruh item di keranjang. */
function cart_count()
{
    return array_sum($_SESSION['cart'] ?? []);
}

/**
 * Ambil isi keranjang dari session lalu cari detail buku di database.
 */
function cart_items(PDO $pdo)
{
    $cart = $_SESSION['cart'] ?? [];

    if (!$cart) {
        return [];
    }

    $ids = array_keys($cart);
    $placeholders = implode(
        ',',
        array_fill(0, count($ids), '?')
    );

    $stmt = $pdo->prepare(
        "SELECT *
         FROM books
         WHERE id IN ($placeholders)
         ORDER BY id ASC"
    );

    $stmt->execute($ids);
    $rows = $stmt->fetchAll();

    $cleanRows = [];

    foreach ($rows as $row) {
        if ((int)$row['stock'] <= 0) {
            unset($_SESSION['cart'][$row['id']]);
            continue;
        }

        $row['qty'] = max(0, (int) ($cart[$row['id']] ?? 0));
        if ($row['qty'] > 0) {
            $row['subtotal'] = $row['qty'] * (float) $row['price'];
            $cleanRows[] = $row;
        } else {
            unset($_SESSION['cart'][$row['id']]);
        }
    }

    return $cleanRows;
}

/** Hitung total harga keranjang. */
function cart_total(PDO $pdo)
{
    $items = cart_items($pdo);
    $total = 0;

    foreach ($items as $item) {
        $total += $item['subtotal'];
    }

    return $total;
}

/**
 * Simpan atau ambil pesan flash dari session.
 */
function flash($type = null, $message = null)
{
    if ($type !== null && $message !== null) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
        ];

        return null;
    }

    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return $flash;
}
?>
