<?php
/**
 * File: tools/usk_practice.php
 * Deskripsi: Demonstrasi CLI untuk array, pengurutan, file I/O, dan pengukuran.
 * Initial State: PHP CLI tersedia; script dijalankan dari terminal.
 * Final State: Contoh array dan file ditampilkan; file sementara dihapus.
 * Author: [Isi nama peserta]
 * Version: 1.1 | Tanggal revisi: 04 November 2026
 *
 * Jalankan dari folder utama project:
 * php tools/usk_practice.php
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Script demonstrasi ini hanya boleh dijalankan melalui PHP CLI.\n");
}

$startTime = microtime(true);

// 1. Indexed array: kumpulan kategori dengan indeks angka.
$categories = ['Novel', 'Komik', 'Fantasy', 'Teknologi'];

// 2. Associative array: satu data buku dengan pasangan key-value.
$featuredBook = [
    'title' => 'Belajar Pemrograman Web',
    'price' => 75000,
    'stock' => 8,
];

// 3. Multidimensional array: daftar buku yang setiap elemennya associative array.
$books = [
    ['title' => 'Buku A', 'price' => 50000, 'stock' => 5],
    ['title' => 'Buku B', 'price' => 35000, 'stock' => 7],
    ['title' => 'Buku C', 'price' => 90000, 'stock' => 2],
];

// 4. count() menghitung jumlah elemen pada array.
$categoryCount = count($categories);
$bookCount = count($books);

// 5. sort() mengurutkan indexed array secara ascending.
$sortedCategories = $categories;
sort($sortedCategories, SORT_STRING);

// 6. usort() mengurutkan array buku berdasarkan harga termurah.
$booksByPrice = $books;
usort($booksByPrice, static function (array $left, array $right): int {
    return $left['price'] <=> $right['price'];
});

// 7. File I/O: tulis data ke file sementara, baca kembali, lalu hapus.
$filePath = tempnam(sys_get_temp_dir(), 'rastorebook-usk-');
if ($filePath === false) {
    fwrite(STDERR, "Gagal membuat file sementara.\n");
    exit(1);
}

$fileContent = "RAStory Bookstore - Demonstrasi File I/O\n";
$fileContent .= "Jumlah kategori: {$categoryCount}\n";
$fileContent .= "Jumlah buku: {$bookCount}\n";

$writtenBytes = file_put_contents($filePath, $fileContent, LOCK_EX);
if ($writtenBytes === false) {
    @unlink($filePath);
    fwrite(STDERR, "Gagal menulis file sementara.\n");
    exit(1);
}

$readContent = file_get_contents($filePath);
@unlink($filePath);

$elapsedMs = (microtime(true) - $startTime) * 1000;
$peakMemoryMb = memory_get_peak_usage(true) / 1024 / 1024;

// Tampilkan hasil agar dapat dijelaskan saat demonstrasi praktik USK.
echo "=== DEMONSTRASI PEMROGRAMAN TERSTRUKTUR ===\n\n";
echo "Indexed array:\n";
print_r($categories);
echo "\nAssociative array:\n";
print_r($featuredBook);
echo "\nMultidimensional array:\n";
print_r($books);
echo "\nJumlah kategori: {$categoryCount}\n";
echo "Jumlah buku: {$bookCount}\n";
echo "Kategori setelah sort(): " . implode(', ', $sortedCategories) . "\n";
echo "Buku dari harga termurah (usort()):\n";
foreach ($booksByPrice as $book) {
    echo '- ' . $book['title'] . ': Rp ' . number_format($book['price'], 0, ',', '.') . "\n";
}
echo "\nIsi file setelah file_put_contents() lalu file_get_contents():\n";
echo $readContent;
echo "Waktu eksekusi demonstrasi: " . number_format($elapsedMs, 3) . " ms\n";
echo "Puncak memori proses: " . number_format($peakMemoryMb, 2) . " MB\n";
echo "File sementara sudah dihapus setelah dibaca.\n";
