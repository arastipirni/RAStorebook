# Catatan Revisi Persiapan USK

Tanggal revisi: 05 November 2026

## Perubahan kode

1. Menambahkan token CSRF pada formulir POST dan validasi token di endpoint terkait.
2. Mengubah aksi tambah/hapus keranjang menjadi POST, bukan GET.
3. Mengubah hapus buku dan kategori menjadi POST dengan konfirmasi.
4. Menambahkan atribut keamanan cookie sesi: HttpOnly, SameSite=Lax, dan Secure jika HTTPS.
5. Menghapus perubahan skema database dari file koneksi agar `database.php` hanya membuat koneksi.
6. Menambahkan fallback karakter awal nama jika ekstensi mbstring tidak tersedia.
7. Menambahkan komentar dokumentasi awal file PHP dengan deskripsi, initial state, final state, author placeholder, versi, dan tanggal.
8. Menambahkan script demonstrasi CLI untuk array, sorting, file I/O, waktu eksekusi, dan penggunaan memori.
9. Menambahkan panduan pemetaan unit kompetensi, daftar dependency, rancangan UI, dan rencana pengujian.
10. Menambahkan alias cover `caller.jpg` agar nama file pada data contoh SQL tersedia pada folder gambar.
11. Mengubah logout pengguna dan admin menjadi POST dengan token CSRF.
12. Mengecilkan thumbnail bukti transfer pada riwayat pembelian menjadi 80 × 100 px di desktop dan 72 × 90 px di layar kecil; gambar tetap dapat diklik untuk melihat ukuran penuh.
13. Menambahkan versi cache pada stylesheet profil agar perubahan ukuran thumbnail langsung terbaca browser.

## Hal yang belum dapat dinyatakan selesai tanpa pengujian pada komputer peserta

- Pengujian fungsional penuh memakai Apache dan MySQL XAMPP.
- Pengukuran performa halaman website sebenarnya.
- Pengisian versi aktual PHP, Apache, dan MySQL/MariaDB.
- Pelampiran mock-up asli dari alat desain.
- Pengisian nama peserta pada header dokumentasi source code.
- Verifikasi lisensi distribusi software yang benar-benar terpasang.

## Catatan kompatibilitas database

File `database.sql` sudah memuat struktur `published_year`. Untuk database lama yang sudah berisi data penting, jangan langsung mengimpor skrip instalasi karena skrip menghapus tabel terlebih dahulu. Cadangkan data dan lakukan migrasi terencana.


## Revisi pemisahan area user dan admin (05 November 2026)

- Menghapus tautan **Panel Admin** dari header area pengguna agar tampilan pengguna tidak menampilkan navigasi administrator meskipun sesi admin juga aktif.
- Memastikan entry point login pengguna dan admin tetap terpisah dan masing-masing menulis ke slot sesi sendiri (`user` dan `admin`).
- Menambahkan keterangan bahwa kedua sesi dapat aktif bersamaan pada browser yang sama dan logout salah satu peran tidak menghapus sesi peran lain.
- Mengubah tautan **Lihat Website** pada sidebar admin agar membuka tab baru; dashboard admin tetap terbuka pada tab sebelumnya.
- Menambahkan petunjuk URL dan skenario login bersamaan pada README.


## Pemberitahuan pesanan baru untuk admin (05 November 2026)

- Menambahkan badge jumlah pesanan berstatus `Menunggu` pada menu Pesanan dan area atas dashboard admin.
- Menambahkan ringkasan jumlah serta daftar lima pesanan baru terbaru pada dashboard.
- Menambahkan indikator **Pesanan Baru** pada setiap pesanan yang masih berstatus `Menunggu`.
- Menambahkan endpoint admin `order_notifications.php` yang hanya dapat diakses oleh sesi admin dan mengembalikan data JSON tanpa cache.
- Menambahkan polling setiap 15 detik; pesanan baru yang masuk ketika dashboard terbuka memunculkan pemberitahuan di layar.
- Tidak mengubah struktur database karena status awal `Menunggu` sudah tersedia pada tabel `orders`.
- Menambahkan skenario verifikasi notifikasi pesanan baru ke `docs/RENCANA_PENGUJIAN.md`.


## Penyempurnaan tampilan indikator notifikasi (05 November 2026)

- Mengganti angka pada indikator notifikasi di menu Pesanan dan bagian atas dashboard dengan titik merah minimalis.
- Mengganti emoji lonceng dengan ikon lonceng SVG bergaya outline agar tampil konsisten seperti ikon antarmuka aplikasi.
- Menambahkan animasi pulse yang halus pada titik notifikasi dan menghormati preferensi `prefers-reduced-motion`.
- Jumlah pesanan baru tetap tersedia pada ringkasan dashboard dan atribut aksesibilitas, sedangkan titik disembunyikan jika tidak ada pesanan berstatus `Menunggu`.

## 05 November 2026 — Penyegaran notifikasi dan daftar pesanan admin
- Memperbarui kartu notifikasi kanan atas menggunakan ikon SVG, judul dan keterangan dua tingkat, indikator titik merah, serta tanggal yang terpisah.
- Memperbarui panel pesanan yang menunggu dengan hierarki judul, ringkasan jumlah, tombol menuju seluruh pesanan, tabel yang lebih mudah dibaca, dan status berbentuk badge.
- Memastikan indikator sidebar hanya berupa titik, tanpa angka yang menempel pada label Pesanan.
- Menambahkan pembaruan teks notifikasi saat jumlah pesanan berubah melalui pemeriksaan berkala.

## Revisi tampilan admin — 05 November 2026

- Menyeragamkan tipografi seluruh area admin menggunakan Segoe UI/Arial agar mudah dibaca di Windows.
- Menghapus penggunaan Georgia/serif pada navigasi dan keterangan dashboard admin.
- Menyesuaikan ukuran judul, keterangan, tabel, status, dan tanggal agar hierarkinya lebih jelas.
- Memperluas kartu ringkasan dashboard agar memanfaatkan ruang konten.
- Mengganti ikon emoji pada kartu statistik menjadi ikon SVG sederhana yang konsisten.
- Mempertahankan palet biru RAStory Bookstore serta fungsi notifikasi pesanan.
