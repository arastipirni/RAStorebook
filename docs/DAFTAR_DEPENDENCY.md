# Daftar Dependency dan Komponen RAStory Bookstore

Daftar ini membedakan komponen yang digunakan langsung oleh source code dengan software lingkungan yang menjalankan aplikasi. Versi aktual harus dicatat dari komputer yang dipakai pada saat asesmen.

| Komponen | Jenis | Digunakan untuk | Cara verifikasi |
|---|---|---|---|
| PHP | Runtime server-side | Menjalankan logika aplikasi | `php -v` |
| PDO | Ekstensi PHP | Abstraksi akses database | `php -m` |
| pdo_mysql | Driver PDO | Menghubungkan PDO ke MySQL/MariaDB | `php -m` |
| Fileinfo | Ekstensi PHP | Memeriksa MIME gambar upload | `php -m` |
| Apache | Web server (XAMPP) | Melayani halaman localhost | XAMPP Control Panel |
| MySQL/MariaDB | DBMS | Menyimpan akun, buku, pesanan, dan pesan | phpMyAdmin / `SELECT VERSION()` |
| HTML/CSS | Teknologi browser | Struktur dan style antarmuka | Source `*.php` dan `assets/css/` |
| JavaScript browser | Teknologi browser | Interaksi halaman | `assets/js/app.js` dan inline script yang digunakan |
| PDOStatement | Komponen PDO | Menjalankan query terparameterisasi | Source `prepare()` dan `execute()` |

## Library pihak ketiga

Pada ZIP ini tidak ditemukan `composer.json`, `vendor/`, Bootstrap, jQuery, atau framework PHP pihak ketiga. Oleh karena itu, jangan membuat klaim bahwa project menggunakan library tersebut.

## Lisensi dan pembaruan

- Periksa lisensi software dari distribusi XAMPP, PHP, dan MySQL/MariaDB yang benar-benar digunakan.
- Pertahankan pemberitahuan hak cipta dan lisensi pada library/aset yang ditambahkan.
- Sebelum memperbarui PHP atau ekstensi, buat salinan project dan database.
- Setelah pembaruan, jalankan pemeriksaan sintaks, tes koneksi, upload, login, checkout, dan seluruh skenario pada `RENCANA_PENGUJIAN.md`.
- Catat nama komponen, versi sebelum, versi sesudah, tanggal, alasan pembaruan, hasil pengujian, serta sumber lisensi.

| Komponen | Versi aktual | Lisensi/sumber lisensi | Tanggal pemeriksaan |
|---|---|---|---|
| PHP | Isi saat ujian | Periksa distribusi PHP yang terpasang | __________ |
| XAMPP | Isi saat ujian | Periksa lisensi distribusi XAMPP | __________ |
| MySQL/MariaDB | Isi saat ujian | Periksa distribusi DBMS yang terpasang | __________ |
| PDO / pdo_mysql | Ikut versi PHP | Dokumentasi dan lisensi PHP | __________ |
| Fileinfo | Ikut versi PHP | Dokumentasi dan lisensi PHP | __________ |
