# RAStory Bookstore

Project PHP + MySQL untuk XAMPP.

## Cara menjalankan
1. Salin folder `bookstore` ke `htdocs`.
2. Nyalakan Apache dan MySQL di XAMPP.
3. Buka phpMyAdmin.
4. Import **satu file** `database.sql`.
5. Buka `http://localhost/bookstore/`.
6. Login pengguna: `http://localhost/bookstore/login.php`.
7. Login admin terpisah: `http://localhost/bookstore/admin/login.php`.
8. Setelah login admin, dashboard dapat dibuka melalui `http://localhost/bookstore/admin/`.

## Akun demo
- Admin (hanya melalui halaman admin): `bookstore@gmail.com` / `book123`
- Pengguna (hanya melalui halaman pengguna): `user@bookstore.local` / `User123!`

Sesi pengguna dan admin terpisah. Keduanya dapat aktif pada browser yang sama. Untuk membuka dashboard, tetap harus masuk menggunakan akun dengan role `admin`; akun pengguna biasa tidak mendapatkan akses admin.

## Fitur tambahan sesuai Word
- Logo RAStory Bookstore baru.
- Semua teks tampilan utama menggunakan Bahasa Indonesia.
- Navigasi memiliki efek bayangan dan penanda halaman aktif.
- Buku dengan stok 0 menampilkan **Tidak Tersedia** warna merah pada daftar dan detail buku.
- Buku yang stoknya habis tidak dapat dimasukkan ke keranjang.
- Checkout mendukung **Bayar di Tempat (COD)** dan **Transfer Bank**.
- Transfer Bank menampilkan pilihan nama bank, kolom nomor rekening, dan upload bukti transfer.
- Bukti transfer tersimpan di `assets/uploads/transfers/` dan tampil di admin pada daftar pesanan.
- Nomor pesanan menggunakan format umum seperti `ORD-20260912-A1B2C3`, bukan nomor tampilan `#1`.
- Cover buku pada admin dapat diunggah langsung sebagai gambar tanpa mengetik nama file secara manual.
- Urutan kategori baru otomatis mengambil nomor berikutnya dari urutan terbesar yang ada.
- Gambar pada folder aset website ditingkatkan resolusinya dan dipertajam agar tampak lebih jelas.
- Database proyek disatukan dalam satu file `database.sql`.

## Catatan
Folder `assets/uploads/transfers` harus bisa ditulis oleh Apache agar bukti transfer dapat disimpan.
