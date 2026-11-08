# Migrasi Database Lama

File `config/database.php` sekarang hanya membuat koneksi. Perubahan struktur tabel dilakukan melalui skrip SQL/manual, bukan setiap request website.

Database instalasi terbaru pada `database.sql` sudah memiliki kolom `books.published_year`. Jika database lama kamu belum memiliki kolom tersebut:

1. Backup database `bookstore` terlebih dahulu melalui phpMyAdmin.
2. Buka database `bookstore`, pilih tab SQL.
3. Jalankan perintah berikut **hanya jika** kolom `published_year` belum ada:

```sql
ALTER TABLE books
ADD COLUMN published_year SMALLINT UNSIGNED NULL AFTER author;
```

4. Periksa kembali struktur tabel `books`.
5. Jangan import ulang `database.sql` pada database yang berisi data penting karena skrip instalasi melakukan DROP TABLE.
