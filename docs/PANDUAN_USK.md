# Panduan Pemetaan Project RAStory Bookstore terhadap Penilaian USK

Dokumen ini adalah panduan bukti project, bukan pengganti demonstrasi langsung dan bukan jaminan kelulusan. Peserta tetap harus menunjukkan kode, menjelaskan logika, dan menjalankan skenario pengujian di lingkungan penguji.

## Unit 1 — J.620100.005.02: Mengimplementasikan User Interface

### Yang dinilai

- Identifikasi kebutuhan pengguna dan admin.
- Identifikasi komponen UI dialog/form.
- Penjelasan urutan akses komponen.
- Pembuatan simulasi/mock-up.
- Penerapan menu dan tata letak.
- Pengaturan komponen aktif/pasif sesuai alur.
- Penentuan style dan pengubahan rancangan menjadi aplikasi.

### Bukti pada project

- Halaman katalog: `index.php`.
- Detail buku: `detail.php`.
- Login dan pendaftaran: `login.php`, `register.php`.
- Keranjang dan checkout: `cart.php`, `checkout.php`.
- Profil dan riwayat: `profile.php`.
- Antarmuka admin: folder `admin/`.
- Style: `assets/css/style.css` dan `assets/css/profile.css`.
- Referensi visual yang disertakan: `assets/reference/`.

### Yang harus disiapkan peserta

- Simpan mock-up asli dari Figma/alat desain sebagai bukti, bukan hanya screenshot website jadi.
- Tunjukkan minimal mock-up login, katalog, detail buku, keranjang, checkout, profil, dashboard admin, dan kelola buku.
- Jelaskan aktor, tujuan halaman, komponen input, tombol, tabel, status, dan kondisi stok habis.
- Cocokkan satu per satu mock-up dengan hasil implementasi.

## Unit 2 — J.620100.010.01: Menerapkan Perintah Eksekusi Bahasa Pemrograman Berbasis Teks, Grafik, dan Multimedia

### Yang dinilai

- Identifikasi tools dan mekanisme running.
- Identifikasi parameter konfigurasi dan lokasi source code.
- Menjalankan source code dengan tools yang dipilih.
- Membedakan running, debugging, dan executable.
- Membuktikan output sesuai skenario serta menelusuri kegagalan.

### Bukti pada project

- Source PHP berada di folder project di bawah `htdocs`.
- Konfigurasi koneksi: `config/database.php`.
- Database: `database.sql`.
- CSS, JavaScript, dan gambar berada di `assets/`.

### Demonstrasi yang wajib dilakukan

1. Jalankan Apache dan MySQL pada XAMPP.
2. Import database pada phpMyAdmin.
3. Buka website di browser melalui `http://localhost/bookstore/`.
4. Perlihatkan satu alur pengguna sampai checkout.
5. Perlihatkan login admin dan perubahan status pesanan.
6. Tunjukkan cara memeriksa error melalui pesan browser, log Apache/PHP, dan tab Network browser.

## Unit 3 — J.620100.015.01: Menyusun Fungsi, File atau Sumber Daya Pemrograman dalam Organisasi yang Rapi

### Yang dinilai

- Penamaan file, fungsi, variabel, konstanta, dan resource.
- Dokumentasi fungsi/program: deskripsi, initial state, final state, author, versi/tanggal.
- Komentar yang membantu pemahaman.
- Folder/subfolder sesuai isi.
- README yang menerangkan struktur dan resource.

### Bukti pada project

- Fungsi bersama: `includes/functions.php`.
- Konfigurasi: `config/database.php`.
- Komponen berulang: `includes/`.
- Sumber tampilan: `assets/`.
- Dokumentasi: `README.md` dan folder `docs/`.

### Yang masih perlu diisi/dibuktikan peserta

- Ganti `[Isi nama peserta]` pada header source file dengan nama peserta yang sebenarnya.
- Tambahkan mock-up asli ke folder bukti UI bila diminta penguji.
- Pastikan peserta dapat menerangkan fungsi file utama tanpa hanya membaca nama file.

## Unit 4 — J.620100.016.01: Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices

### Yang dinilai

- Konsistensi coding guidelines.
- Kesesuaian struktur program dengan paradigma yang digunakan.
- Penanganan error.
- Efisiensi penggunaan resource.
- Kemudahan interaksi pengguna.

### Bukti pada project

- PDO prepared statement untuk query dinamis.
- `password_hash()` dan `password_verify()` untuk password.
- Helper `e()` untuk escape output HTML.
- Token CSRF pada formulir POST.
- Validasi tipe dan ukuran upload.
- Transaksi database pada checkout.
- `try/catch` pada bagian yang berisiko mengalami kegagalan.

### Pengukuran performa

- Jalankan `php tools/usk_practice.php` untuk memperoleh waktu eksekusi dan puncak memori demonstrasi.
- Untuk performa website sebenarnya, ukur request halaman melalui browser DevTools/Network atau alat profiling PHP. Catat halaman, jumlah data, kondisi pengujian, waktu, dan penggunaan memori.
- Jangan mengklaim angka performa seluruh website hanya dari script demonstrasi.

## Unit 5 — J.620100.017.02: Mengimplementasikan Pemrograman Terstruktur

### Yang dinilai

- Tipe data dan syntax.
- Percabangan, perulangan, operator.
- Input dan output.
- Prosedur dan fungsi.
- Array: dimensi, tipe elemen, panjang, dan pengurutan.
- File handling: menulis dan membaca file.
- Pemeriksaan serta koreksi kesalahan syntax.

### Bukti pada project

- Percabangan dan validasi: `login.php`, `checkout.php`, `admin/books.php`.
- Perulangan: daftar buku, item keranjang, dan riwayat di `index.php`, `cart.php`, `profile.php`.
- Fungsi: `includes/functions.php`.
- Array: data hasil `fetchAll()`, `$_SESSION['cart']`, dan array pesanan.
- Upload file: `admin/books.php` dan `checkout.php`.
- Demonstrasi tambahan array sort dan file read/write: `tools/usk_practice.php`.

### Hal yang harus dapat dijelaskan

- Indexed, associative, dan multidimensional array.
- `count()`, `sort()`, `asort()`, `ksort()`, dan `usort()`.
- Perbedaan `$_GET`, `$_POST`, `$_SESSION`, dan `$_FILES`.
- Perbedaan function yang mengembalikan nilai dan prosedur yang melakukan tindakan.
- Perbedaan syntax error, runtime error, dan logic error.

## Unit 6 — J.620100.019.02: Menggunakan Library atau Komponen Pre-existing

### Yang dinilai

- Memilih komponen reuse yang sesuai.
- Menjelaskan manfaat dan efisiensi komponen.
- Memperhatikan lisensi/hak cipta.
- Mengidentifikasi dependency dan menghindari komponen obsolete.
- Mengintegrasikan dan memperbarui komponen.

### Komponen yang benar-benar digunakan

- PDO dan PDOStatement untuk akses database.
- Fileinfo (`finfo`) untuk mendeteksi MIME file upload.
- Fungsi bawaan PHP untuk hashing password, escaping output, array, file, dan sesi.
- HTML, CSS, dan JavaScript browser untuk antarmuka.

Project tidak menggunakan Composer, Bootstrap, jQuery, atau framework PHP pihak ketiga pada versi ini. Jangan menyebutkan komponen yang tidak benar-benar ada di source code.

### Yang harus disiapkan peserta

- Tunjukkan kode `new PDO(...)` di `config/database.php`.
- Tunjukkan validasi `finfo` di fungsi upload.
- Perlihatkan modul PHP aktif (`php -m`) dan versi PHP (`php -v`).
- Isi versi aktual PHP, MySQL/MariaDB, Apache, dan OS berdasarkan komputer yang dipakai saat ujian.
- Baca lisensi distribusi PHP, XAMPP, dan MySQL/MariaDB yang benar-benar terpasang; simpan sumber atau file lisensinya jika diminta.
- Catat kapan dependency diperiksa/diperbarui dan jalankan ulang pengujian setelah pembaruan.

## Catatan penting

Status “sudah ada di kode” tidak sama dengan “sudah terbukti saat asesmen”. Penguji dapat meminta peserta membuka source code, menjalankan aplikasi, mengubah data, dan menjelaskan hasilnya. Gunakan `docs/RENCANA_PENGUJIAN.md` untuk mencatat hasil demonstrasi nyata.
