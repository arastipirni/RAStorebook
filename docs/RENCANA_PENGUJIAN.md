# Rencana Pengujian RAStory Bookstore

Isi kolom hasil aktual setelah pengujian benar-benar dilakukan di XAMPP. Jangan mengisi status lulus sebelum mencoba skenario.

| No. | Skenario | Langkah ringkas | Hasil yang diharapkan | Hasil aktual / bukti |
|---|---|---|---|---|
| 1 | Beranda | Buka `/bookstore/` | Katalog dan navigasi tampil | Belum diuji |
| 2 | Pencarian | Cari judul/penulis yang tersedia | Daftar sesuai kata pencarian | Belum diuji |
| 3 | Kategori | Pilih satu kategori | Buku sesuai kategori tampil | Belum diuji |
| 4 | Detail buku | Buka salah satu detail | Cover, judul, harga, stok, deskripsi tampil | Belum diuji |
| 5 | Registrasi valid | Daftarkan email baru | Akun role user tersimpan | Belum diuji |
| 6 | Email duplikat | Daftarkan email yang sudah ada | Sistem menolak dan memberi pesan | Belum diuji |
| 7 | Login user | Masuk dengan akun user | Masuk ke area pengguna | Belum diuji |
| 8 | Isolasi role | Akses `/admin/` sebagai user | Pengguna ditolak/diarahkan ke login admin | Belum diuji |
| 9 | Login admin | Masuk melalui `/admin/login.php` | Dashboard admin tampil | Belum diuji |
| 10 | Tambah buku | Admin mengisi form buku dan cover | Buku tersimpan dan tampil di katalog | Belum diuji |
| 11 | Ubah buku | Ubah harga/stok buku | Data berubah sesuai input | Belum diuji |
| 12 | Hapus buku | Gunakan tombol hapus | Konfirmasi tampil; buku dihapus jika dikonfirmasi | Belum diuji |
| 13 | Kategori | Tambah, ubah, hapus kategori | Perubahan tersimpan; kategori yang masih dipakai ditangani | Belum diuji |
| 14 | Keranjang | Tambah buku dan ubah quantity | Jumlah dan subtotal diperbarui | Belum diuji |
| 15 | Hapus keranjang | Hapus satu item | Item hilang dari keranjang | Belum diuji |
| 16 | Stok habis | Coba tambah buku stok 0 | Sistem menolak penambahan | Belum diuji |
| 17 | Checkout COD | Isi alamat, telepon, pilih COD | Order dan detail tersimpan; stok berkurang | Belum diuji |
| 18 | Checkout transfer | Pilih transfer dan unggah gambar valid | Bukti disimpan dan path tercatat pada order | Belum diuji |
| 19 | Upload tidak valid | Unggah file bukan JPG/PNG/WEBP | Upload ditolak | Belum diuji |
| 20 | Bukti pada profil | Buka profil setelah transfer | Foto bukti muncul pada riwayat order transfer | Belum diuji |
| 21 | Bukti pada admin | Buka pesanan sebagai admin | Admin dapat melihat bukti transfer | Belum diuji |
| 22 | Status pesanan | Ubah status pesanan admin | Status terbaru tersimpan | Belum diuji |
| 23 | Kontak | Kirim pesan valid | Pesan tersimpan dan dapat dibaca admin | Belum diuji |
| 24 | CSRF | Kirim POST tanpa token / token salah | Request ditolak dengan status 419 | Belum diuji |
| 25 | Logout | Logout user/admin | Hanya sesi role terkait yang diakhiri | Belum diuji |

## Catatan hasil

- Tanggal pengujian: ____________________
- Nama penguji: ____________________
- Versi PHP: ____________________
- Versi MySQL/MariaDB: ____________________
- Browser: ____________________
- Bukti screenshot/video: ____________________
- Temuan dan perbaikan: ____________________


## Skenario tambahan: sesi user dan admin bersamaan

1. Buka tab pertama dan login melalui `login.php` menggunakan akun dengan role `user`.
2. Pastikan tab pertama menampilkan halaman katalog pengguna dan tidak menampilkan tombol/menu/tautan **Panel Admin**.
3. Tanpa logout dari tab pertama, buka tab kedua menuju `admin/login.php`.
4. Login menggunakan akun dengan role `admin`; tab kedua harus masuk ke dashboard admin.
5. Kembali ke tab pertama dan muat ulang; sesi pengguna tetap aktif dan tampilannya tetap area pengguna tanpa menu admin.
6. Dari sidebar dashboard admin, klik **Lihat Website**; area publik terbuka pada tab baru dan dashboard admin tetap terbuka.
7. Logout pengguna dari tab pengguna; pastikan sesi admin masih aktif.
8. Login pengguna kembali, kemudian logout admin; pastikan sesi pengguna tetap aktif.

**Hasil yang diharapkan:** sesi `$_SESSION['user']` dan `$_SESSION['admin']` dapat hidup bersamaan. Halaman admin tetap dilindungi `require_admin()`, dan halaman publik tidak memunculkan menu administrasi.


## Skenario tambahan: pemberitahuan pesanan baru admin

26. Login admin dan biarkan dashboard tetap terbuka. Dari tab/browser lain, login sebagai pengguna lalu buat pesanan baru dengan checkout COD atau Transfer Bank. Dalam waktu maksimal sekitar 15 detik, titik merah pada ikon notifikasi dan menu Pesanan harus muncul, sedangkan jumlah numerik di ringkasan dashboard diperbarui; pemberitahuan di layar juga harus muncul tanpa memuat ulang dashboard.
27. Klik notifikasi atau menu Pesanan. Pesanan yang baru dibuat harus terlihat dengan label **Pesanan Baru** dan status `Menunggu`.
28. Ubah status pesanan menjadi `Diproses`. Setelah halaman dimuat ulang atau polling berikutnya, jumlah pesanan baru harus berkurang.
29. Periksa saat tidak ada pesanan berstatus `Menunggu`; dashboard harus menampilkan informasi belum ada pesanan baru dan titik merah pada ikon notifikasi serta menu Pesanan disembunyikan.

**Definisi pesanan baru:** pesanan baru dihitung berdasarkan status `Menunggu`. Saat admin mengubah statusnya, pesanan tersebut tidak lagi dihitung sebagai pemberitahuan baru. Fitur memakai data tabel `orders` yang sudah tersedia dan tidak memerlukan migrasi database.
