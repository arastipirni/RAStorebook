# Rancangan User Interface RAStory Bookstore

Dokumen ini menjelaskan kebutuhan dan pemetaan antarmuka yang telah diimplementasikan. Untuk bukti mock-up asli, lampirkan file desain/wireframe dari alat desain yang digunakan (misalnya Figma) ke folder `docs/bukti-ui/` sebelum asesmen. Gambar di `assets/reference/` adalah referensi visual yang disertakan pada project; jangan menyatakannya sebagai file mock-up yang dapat diedit jika memang hanya gambar referensi.

## Aktor dan kebutuhan

| Aktor | Kebutuhan | Halaman/komponen |
|---|---|---|
| Pengunjung | Menjelajahi koleksi buku | Beranda, pencarian, kategori, detail |
| Pengguna terdaftar | Mengelola pembelian | Login, profil, keranjang, checkout, riwayat |
| Administrator | Mengelola konten dan transaksi | Login admin, dashboard, buku, kategori, pengguna, pesanan, pesan |

## Komponen antarmuka

| Halaman | Komponen | Interaksi |
|---|---|---|
| Login pengguna | Email, password, tombol masuk, tautan daftar | Validasi dan autentikasi |
| Beranda | Navigasi, pencarian, filter kategori, kartu buku, tombol detail/keranjang | Menjelajah dan memilih buku |
| Detail buku | Cover, informasi, stok, quantity, tombol keranjang | Memilih jumlah buku |
| Keranjang | Tabel item, quantity, subtotal, hapus, lanjut checkout | Mengubah isi keranjang |
| Checkout | Alamat, telepon, select pembayaran, upload bukti | Membuat pesanan |
| Profil | Identitas, status pesanan, rincian buku, bukti transfer | Melihat riwayat |
| Admin buku | Form, input cover, tabel, edit/hapus | CRUD data buku |
| Admin pesanan | Tabel pesanan, bukti transfer, select status | Memperbarui status |

## Urutan proses pengguna

Beranda → Detail Buku → Keranjang → Checkout → Konfirmasi Pesanan → Profil/Riwayat.

## Kondisi komponen aktif/pasif

- Tombol keranjang tidak tersedia untuk buku dengan stok 0.
- Bagian upload bukti transfer digunakan ketika metode Transfer Bank dipilih.
- Area admin hanya tersedia ketika sesi dengan role admin aktif.
- Formulir menampilkan pesan validasi ketika data wajib belum lengkap.

## Daftar mock-up yang harus dilampirkan

Buat/simpan mock-up asli untuk: (1) login/register, (2) beranda/katalog, (3) detail buku, (4) keranjang, (5) checkout COD dan transfer, (6) profil/riwayat, (7) dashboard admin, (8) kelola buku/kategori, dan (9) daftar pesanan.
